<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once 'app/vendor/autoload.php';
class AccountController
{
    private $accountModel;
    private $db;
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }
    function register()
    {
        include_once 'app/views/account/register.php';
    }
    public function login()
    {
        include_once 'app/views/account/login.php';
    }
    function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $errors = [];
            if (empty($username)) {
                $errors['username'] = "Vui long nhap userName!";
            }
            if (empty($fullName)) {
                $errors['fullname'] = "Vui long nhap fullName!";
            }
            if (empty($password)) {
                $errors['password'] = "Vui long nhap password!";
            }
            if ($password != $confirmPassword) {
                $errors['confirmPass'] = "Mat khau va xac nhan chua dung";
            }
            //kiểm tra username đã được đăng ký chưa?
            $account = $this->accountModel->getAccountByUsername($username);
            if ($account) {
                $errors['account'] = "Tai khoan nay da co nguoi dang ky!";
            }
            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $result = $this->accountModel->save($username, $fullName, $password);
                if ($result) {
                    header('Location: /webbanhang/account/login');
                }
            }
        }
    }
    function logout()
    {
        // Hủy session khi người dùng đăng xuất
        session_start();  // Khởi tạo session nếu chưa có
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        session_destroy();  // Hủy session
    
        // Chuyển hướng về trang sản phẩm sau khi đăng xuất
        header('Location: /webbanhang/product');
        exit;
    }
    
  



    public function checkLogin()
    {
        // Kiểm tra xem liệu form đã được submit
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
    
            // Lấy tài khoản từ cơ sở dữ liệu theo username
            $account = $this->accountModel->getAccountByUserName($username);
    
            if ($account) {
                $pwd_hashed = $account->password;
                
                // Kiểm tra mật khẩu
                if (password_verify($password, $pwd_hashed)) {
                    // Khởi tạo session và lưu thông tin người dùng
                    session_start();
                    $_SESSION['username'] = $account->username; // Lưu username vào session
                    $_SESSION['role'] = $account->role; // Lưu role vào session (nếu có)
    
                    // Sau khi đăng nhập thành công, chuyển hướng người dùng tới trang sản phẩm
                    header('Location: /webbanhang/product/list');
                    exit;
                } else {
                    echo "Password incorrect.";
                }
            } else {
                echo "Không tìm thấy tài khoản với tên đăng nhập này.";
            }
        }
    }
    





    public function googleLogin()
    {
        // Cấu hình Google OAuth
        $clientID = '51035927925-6gso3uuoam6efc12t1lad4mqplc6u0p5.apps.googleusercontent.com';
        $clientSecret = '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';
        $redirectUri = 'http://localhost/WEBBANHANG/account/login';
    
        $client = new Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope("email");
        $client->addScope("profile");
    
        // Xác thực
        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token['access_token']);
    
            // Lấy thông tin từ Google
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            $email = $google_account_info->email;
            $name = $google_account_info->name;
            $googleId = $google_account_info->id;
    
            // Kiểm tra xem tài khoản Google này đã tồn tại trong DB chưa
            $existingAccount = $this->accountModel->getAccountByUsername($email);
    
            if ($existingAccount) {
                
                // Nếu tài khoản đã tồn tại, đăng nhập
                session_start();
                $_SESSION['username'] = $existingAccount->username;
                $_SESSION['role'] = $existingAccount->role_id;
    
            } else {
                // Nếu chưa tồn tại, thêm tài khoản mới vào DB
                $defaultPassword = password_hash($googleId, PASSWORD_BCRYPT); // Hash Google ID làm mật khẩu
                $roleId = 5; // Role mặc định là customer
    
                // Lưu tài khoản vào DB
                $this->accountModel->save($email, $name, $defaultPassword, $roleId, $googleId);
    
                // Tạo session
                session_start();
                $_SESSION['username'] = $email;
                $_SESSION['role'] = $roleId;
            }
    
            // Chuyển hướng sau khi đăng nhập thành công
            header('Location: /WEBBANHANG/product/list');
            exit();
        } else {
            // Tạo URL đăng nhập Google
            $loginUrl = $client->createAuthUrl();
            echo "<a href='$loginUrl'>Login with Google</a>";
        }
    }



    public function docs()
    {
        header('Content-Type: application/json');
        $documentation = [
            'overview' => 'The ProductApiController is designed to handle API requests related to managing products.',
            'endpoints' => [
                [
                    'name' => 'Get All Products',
                    'method' => 'GET',
                    'endpoint' => '/productapi',
                    'description' => 'Retrieves a list of all products.',
                    'response_example' => [
                        [
                            'id' => 1,
                            'name' => 'Product 1',
                            'description' => 'Description 1',
                            'price' => 100,
                            'category_id' => 2
                        ]
                    ]
                ],
                [
                    'name' => 'Get Product by ID',
                    'method' => 'GET',
                    'endpoint' => '/productapi/show/{id}',
                    'description' => 'Retrieves details of a specific product by ID.',
                    'parameters' => [
                        'id' => 'The ID of the product.'
                    ],
                    'response_example' => [
                        'id' => 1,
                        'name' => 'Product 1',
                        'description' => 'Description 1',
                        'price' => 100,
                        'category_id' => 2
                    ]
                ],
                // Thêm các endpoint khác ở đây...
            ]
        ];
    
        echo json_encode($documentation, JSON_PRETTY_PRINT);
    }
    

}
