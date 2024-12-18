<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
class ProductApiController
{
    private $productModel;
    private $db;
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }




    // Phương thức xác thực token
    private function authenticate()
    {
        // Lấy token từ header Authorization
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized access']);
            exit;
        }

        // Tách token
        $authHeader = explode(' ', $headers['Authorization']);
        if (count($authHeader) !== 2 || $authHeader[0] !== 'Bearer') {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid token format']);
            exit;
        }

        $token = $authHeader[1];

        // So sánh token với session
        if (!isset($_SESSION['api_token']) || $_SESSION['api_token'] !== $token) {
            http_response_code(403);
            echo json_encode(['message' => 'Forbidden: Invalid token']);
            exit;
        }
    }




    // Lấy danh sách sản phẩm
    public function index()
    {

        header('Content-Type: application/json');
        $products = $this->productModel->getProducts();
        echo json_encode($products);
    }



    // Lấy thông tin sản phẩm theo ID
    public function show($id = null)
    {
        header('Content-Type: application/json');

        // Kiểm tra xem `$id` có được truyền hay không
        if ($id === null) {
            http_response_code(400);
            echo json_encode(['message' => 'Product ID is required']);
            return;
        }

        $product = $this->productModel->getProductById($id);
        if ($product) {
            echo json_encode($product);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Product not found']);
        }
    }




    // Thêm sản phẩm mới
    public function store()
    {
        header('Content-Type: application/json');

        // Kiểm tra xem dữ liệu được gửi đến có file hay không
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';
        $category_id = $_POST['category_id'] ?? null;
        $image = null;

        // Kiểm tra và lưu file ảnh nếu có
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $fileName = basename($_FILES['image']['name']);
            $targetFilePath = $uploadDir . $fileName;

            // Di chuyển file vào thư mục đích
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $image = $targetFilePath;
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to upload image']);
                return;
            }
        }

        // Gọi phương thức thêm sản phẩm với tham số ảnh
        $result = $this->productModel->addProduct(
            $name,
            $description,
            $price,
            $category_id,
            $image
        );

        if (is_array($result)) {
            http_response_code(400);
            echo json_encode(['errors' => $result]);
        } else {
            http_response_code(201);
            echo json_encode(['message' => 'Product created successfully']);
        }
    }




    // Cập nhật sản phẩm theo ID
    public function update($id)
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? '';
        $category_id = $data['category_id'] ?? null;
        $result = $this->productModel->updateProduct(
            $id,
            $name,
            $description,
            $price,
            $category_id,
            null
        );
        if ($result) {
            echo json_encode(['message' => 'Product updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Product update failed']);
        }
    }




    public function destroy($id )
    {
        header('Content-Type: application/json');
    
        // If no ID is provided, return an error
        if ($id === null) {
            http_response_code(400);
            echo json_encode(['message' => 'Product ID is required']);
            return;
        }
    
        // Rest of your existing destroy method code...
        if (!is_numeric($id) || $id <= 0) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid product ID']);
            return;
        }
    
        $result = $this->productModel->deleteProduct($id);
    
        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'Product deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Product deletion failed', 'error' => 'Internal server error']);
        }
    }





}
