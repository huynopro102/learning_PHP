<?php include 'app/views/shares/header.php'; ?>
<div class="container">
    <h1 class="my-4">Chỉnh Sửa Quyền Tài Khoản</h1>
    
    <form method="POST" action="/WEBBANHANG/account/updateRole">
        <input type="hidden" name="account_id" value="<?php echo $account->id; ?>">
        
        <div class="card">
            <div class="card-header">
                Thông Tin Tài Khoản
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Tên Đăng Nhập:</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($account->username); ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label for="role_id">Vai Trò:</label>
                    <select name="role_id" id="role_id" class="form-control" required>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role->id; ?>" 
                                <?php echo ($role->id == $account->role_id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($role->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật Quyền</button>
                <a href="/WEBBANHANG/account/list" class="btn btn-secondary">Hủy</a>
            </div>
        </div>
    </form>
</div>
<?php include 'app/views/shares/footer.php'; ?>