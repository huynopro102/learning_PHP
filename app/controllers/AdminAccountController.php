<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');

class AdminAccountController
{
    private $db;
    private $accountModel;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    // List all accounts (for admin)
    public function listAccounts(): void
    {
        // this có nghĩa là gọi "phương thức" hoặc "thuộc tính"
        
        if (!$this->isAdmin()) {
            die("Bạn không có quyền truy cập trang này.");
        }
    
        $query = "SELECT a.*, r.name as role_name 
                  FROM account a 
                  LEFT JOIN user_roles r ON a.role_id = r.id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $accounts = $stmt->fetchAll(PDO::FETCH_OBJ);
    
        include_once 'app/views/admin/account/listAccounts.php';  // Truyền biến $accounts vào view
    }
    

    // Edit user role
    public function editRole($account_id)
    {
        // Check admin permission
        if (!$this->isAdmin()) {
            die("Bạn không có quyền truy cập trang này.");
        }

        // Get account details
        $query = "SELECT * FROM account WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $account_id);
        $stmt->execute();
        $account = $stmt->fetch(PDO::FETCH_OBJ);

        // Get all roles
        $role_query = "SELECT * FROM user_roles";
        $role_stmt = $this->db->prepare($role_query);
        $role_stmt->execute();
        $roles = $role_stmt->fetchAll(PDO::FETCH_OBJ);

        include_once 'app/views/admin/account/editAccount.php';
    }

    // Update user role
    public function updateRole()
    {
        // Check admin permission
        if (!$this->isAdmin()) {
            die("Bạn không có quyền thực hiện thao tác này.");
        }

        $account_id = $_POST['account_id'];
        $role_id = $_POST['role_id'];

        $query = "UPDATE account SET role_id = :role_id WHERE id = :account_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->bindParam(':account_id', $account_id);
        
        if ($stmt->execute()) {
            header('Location: /WEBBANHANG/admin/AdminAccount/listAccounts');
        } else {
            // Handle error
            echo "Cập nhật không thành công";
        }
    }

    // Delete account
    public function deleteAccount($account_id)
    {
        // Check admin permission
        if (!$this->isAdmin()) {
            die("Bạn không có quyền xóa tài khoản.");
        }

        $query = "DELETE FROM account WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $account_id);
        
        if ($stmt->execute()) {
            header('Location: /WEBBANHANG/admin/AdminAccount/listAccounts');
        } else {
            echo "Xóa tài khoản không thành công";
        }
    }

    // Create admin account
    public function createAdminAccount()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullname = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $admin_key = $_POST['admin_key'] ?? '';

            // Validate input
            if (empty($username)) $errors[] = "Tên đăng nhập không được trống"; // $errors[] là thêm 1 phần tử "Tên đăng nhập không được trống" vào cuối mảng ,
            if (empty($fullname)) $errors[] = "Tên đầy đủ không được trống";
            if (empty($password)) $errors[] = "Mật khẩu không được trống";
            if ($password !== $confirm_password) $errors[] = "Mật khẩu không khớp";
            
            // Check admin key (you should replace with a secure method)
            if ($admin_key !== 'your_secure_admin_creation_key') {
                $errors[] = "Khóa quản trị không đúng";
            }

            // Check if username exists
            $existing_user = $this->accountModel->getAccountByUsername($username);
            if ($existing_user) $errors[] = "Tên đăng nhập đã tồn tại";

            if (empty($errors)) {
                // Get admin role ID
                $role_query = "SELECT id FROM user_roles WHERE name = 'admin'";
                $role_stmt = $this->db->prepare($role_query);
                $role_stmt->execute();
                $role = $role_stmt->fetch(PDO::FETCH_ASSOC);

                // Hash password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Insert admin account
                $insert_query = "INSERT INTO account (username, fullname, password, role_id) 
                                 VALUES (:username, :fullname, :password, :role_id)";
                $insert_stmt = $this->db->prepare($insert_query);
                $insert_stmt->bindParam(':username', $username);
                $insert_stmt->bindParam(':fullname', $fullname);
                $insert_stmt->bindParam(':password', $hashed_password);
                $insert_stmt->bindParam(':role_id', $role['id']);

                if ($insert_stmt->execute()) {
                    header('Location: /webbanhang/Account/list');
                    exit();
                } else {
                    $errors[] = "Tạo tài khoản không thành công";
                }
            }

            // If there are errors, show the form again
            include_once 'app/views/account/createAdmin.php';
        } else {
            // Show the form
            include_once 'app/views/account/createAdmin.php';
        }
    }

    // Check if current user is admin , this is a function using common
    private function isAdmin()
    {
        // Kiểm tra nếu người dùng đã đăng nhập hay chưa
        if (!isset($_SESSION['username'])) {
            return false;
        }
    
        $username = $_SESSION['username'];
    
        // Truy vấn kiểm tra role của người dùng trong cơ sở dữ liệu
        $query = "SELECT r.name as role_name, a.role_id 
                  FROM account a
                  JOIN user_roles r ON a.role_id = r.id
                  WHERE a.username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Kiểm tra xem người dùng có tồn tại trong CSDL và có phải là admin không
        if ($result && $result['role_id'] == 1) {
            return true; // Người dùng có role_id = 1 là admin
        }
    
        return false; // Người dùng không phải admin hoặc không có role_id hợp lệ
    }



    public function checklogin()
{
    session_start(); // Bắt đầu session
  
    // Lấy dữ liệu từ form
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Kiểm tra trong cơ sở dữ liệu
    $query = "SELECT * FROM account WHERE username = :username"; // lưu ý :username là 1
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        // Lưu thông tin vào session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['logged_in'] = true;

        header('Location: /WEBBANHANG/Product/listlist'); // Chuyển hướng tới trang chủ
        exit();
    } else {
        // Sai thông tin đăng nhập
        $_SESSION['error'] = 'Sai tên đăng nhập hoặc mật khẩu';
        header('Location: /WEBBANHANG/account/login'); // Quay lại trang login
        exit();
    }
}

    
}