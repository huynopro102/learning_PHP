<?php
// Kiểm tra nếu không có quyền truy cập
if (!isset($account)) {
    die('Tài khoản không tồn tại.');
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa tài khoản</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Chỉnh sửa tài khoản</h2>
    
    <!-- Hiển thị lỗi nếu có -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="/WEBBANHANG/admin/AdminAccount/updateRole">
        <!-- Input tài khoản -->
        <div class="form-group">
            <label for="username">Tên đăng nhập</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($account->username) ?>" readonly>
        </div>

        <!-- Input tên đầy đủ -->
        <div class="form-group">
            <label for="fullname">Tên đầy đủ</label>
            <input type="text" class="form-control" id="fullname" name="fullname" value="<?= htmlspecialchars($account->fullname) ?>" required>
        </div>

        <!-- Chọn quyền -->
        <div class="form-group">
            <label for="role_id">Quyền người dùng</label>
            <select class="form-control" id="role_id" name="role_id" required>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= $role->id ?>" <?= $role->id == $account->role_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($role->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Hidden input chứa account_id -->
        <input type="hidden" name="account_id" value="<?= $account->id ?>">

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="/WEBBANHANG/admin/AdminAccount/listAccounts" class="btn btn-secondary">Hủy bỏ</a>

    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
