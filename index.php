<?php
session_start(); // Khởi tạo session
// Load các file cần thiết
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';

// Kiểm tra nếu người dùng đã đăng nhập và đang cố gắng truy cập trang đăng nhập
if (isset($_SESSION['username']) && $_SERVER['REQUEST_URI'] == '/webbanhang/account/login') {
    header('Location: /webbanhang/product');
    exit;
}

// Nếu phiên đăng nhập Google tồn tại, lưu thông tin user
if (isset($_SESSION['email'])) {
    // Giả lập gán user từ Google vào session
    $_SESSION['username'] = $_SESSION['email'];
    header('Location: /webbanhang/product/list');
    exit;
}

// Lấy URL và xử lý
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlComponents = explode('/', $url);

// Xử lý route cho API
if (isset($urlComponents[0]) && strtolower($urlComponents[0]) === 'productapi') {
    // Controller là ProductApiController
    $controllerName = 'ProductApiController';

    // Action là index thứ 1
    $action = isset($urlComponents[1]) && $urlComponents[1] !== '' 
        ? $urlComponents[1] 
        : 'index'; // Default action

    // Tham số (ví dụ ID) là index thứ 2
    $id = isset($urlComponents[2]) ? $urlComponents[2] : null;
    $params = [$id]; // Chuyển tham số vào mảng
} else {
    // Xử lý các route khác
    if (isset($urlComponents[0]) && strtolower($urlComponents[0]) === 'admin') {
        // Admin routes
        $controllerName = isset($urlComponents[1]) && $urlComponents[1] !== '' 
            ? ucfirst($urlComponents[1]) . 'Controller' 
            : 'AdminDefaultController'; // Default admin controller

        $action = isset($urlComponents[2]) && $urlComponents[2] !== '' 
            ? $urlComponents[2] 
            : 'index'; // Default admin action

        $params = array_slice($urlComponents, 3);
    } else {
        // Routes thông thường
        $controllerName = isset($urlComponents[0]) && $urlComponents[0] !== '' 
            ? ucfirst($urlComponents[0]) . 'Controller' 
            : 'DefaultController'; // Default controller

        $action = isset($urlComponents[1]) && $urlComponents[1] !== '' 
            ? $urlComponents[1] 
            : 'index'; // Default action

        $params = array_slice($urlComponents, 2);
    }
}

// Đường dẫn file controller
$controllerFilePath = 'app/controllers/' . $controllerName . '.php';

// Kiểm tra sự tồn tại của file controller
if (!file_exists($controllerFilePath)) {
    http_response_code(404);
    die('Controller not found');
}

// Load file controller và khởi tạo đối tượng
require_once $controllerFilePath;
if (!class_exists($controllerName)) {
    http_response_code(500);
    die('Controller class not found');
}

$controller = new $controllerName();

// Kiểm tra sự tồn tại của action
if (!method_exists($controller, $action)) {
    http_response_code(404);
    die('Action not found');
}

// Gọi action với các tham số còn lại
try {
    call_user_func_array([$controller, $action], $params);
} catch (Exception $e) {
    http_response_code(500);
    die('An error occurred: ' . $e->getMessage());
}
