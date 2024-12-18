<?php include 'app/views/shares/header.php'; ?>
<?php if (isset($accounts) && !empty($accounts)): ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Đăng Nhập</th>
                    <th>Họ Tên</th>
                    <th>Vai Trò</th>
                    <th>Ngày Tạo</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accounts as $account): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($account->id); ?></td>
                        <td><?php echo htmlspecialchars($account->username); ?></td>
                        <td><?php echo htmlspecialchars($account->fullname ?? 'N/A'); ?></td>
                        <td>
                            <span class="badge <?php echo $account->role_name === 'admin' ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo htmlspecialchars($account->role_name ?? 'Không xác định'); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($account->created_at); ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="/WEBBANHANG/admin/AdminAccount/editRole/<?php echo $account->id; ?>" 
                                   class="btn btn-warning btn-sm">Sửa Quyền</a>
                                   
                                   <a href="/WEBBANHANG/admin/AdminAccount/deleteAccount/<?php echo $account->id; ?>" class="btn btn-danger btn-sm"  onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">Xóa</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-warning" role="alert">
        Không có tài khoản nào.
    </div>
<?php endif; ?>
<?php include 'app/views/shares/footer.php'; ?>