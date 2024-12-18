<?php include 'app/views/shares/header.php'; ?>
<div class="container">
    <h1 class="my-4">Quản Lý Tài Khoản</h1>
    
    <a href="/WEBBANHANG/account/createAdmin" class="btn btn-success mb-3">Tạo Tài Khoản Quản Trị</a>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Fullname</th>
                <th>Vai Trò</th>
                <th>Ngày Tạo</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($accounts as $account): ?>
                <tr>
                    <td><?php echo htmlspecialchars($account->id); ?></td>
                    <td><?php echo htmlspecialchars($account->username); ?></td>
                    <td><?php echo htmlspecialchars($account->fullname ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($account->role_name ?? 'Không xác định'); ?></td>
                    <td><?php echo htmlspecialchars($account->created_at); ?></td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="/WEBBANHANG/Account/editRole/<?php echo $account->id; ?>" 
                               class="btn btn-warning btn-sm">Sửa Quyền</a>

                    

                               <a href="/WEBBANHANG/AccountManagementController/deleteAccount/<?php echo $account->id; ?>" 
   class="btn btn-danger btn-sm" 
   onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">Xóa</a>


                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'app/views/shares/footer.php'; ?>