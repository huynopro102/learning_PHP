<?php include 'app/views/shares/header.php'; ?>
<div class="container">
    <h1 class="my-4">Tạo Tài Khoản Quản Trị</h1>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="/webbanhang/account/saveAdmin">
        <div class="card">
            <div class="card-header">
                Thông Tin Tài Khoản Quản Trị
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="username">Tên Đăng Nhập:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="fullname">Tên Đầy Đủ:</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Mật Khẩu:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Xác Nhận Mật Khẩu:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                
                <div class="form-group">
                    <label for="admin_key">Khóa Quản Trị:</label>
                    <input type="password" class="form-control" id="admin_key" name="admin_key" required>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Tạo Tài Khoản</button>
                <a href="/webbanhang/Account/list" class="btn btn-secondary">Hủy</a>
            </div>
        </div>
    </form>
</div>
<?php include 'app/views/shares/footer.php'; ?>