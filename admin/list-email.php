<?php
include '../config/db.php';
include './includes/nav.php'; // Thêm menu điều hướng

// Xóa danh mục
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM emails WHERE id = $id");
    echo "<script>window.location.href='list-email.php'</script>";
}

$result = $conn->query("SELECT * FROM emails ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-4 text-center">📧 Quản lý Email</h2>

    <table class="table table-hover table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th width="10%">ID</th>
                <th>Tên người dùng</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Ngày gửi</th>
                <th width="20%">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php $stt = 1; ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $stt ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['created_at'] ?? "Trống") ?></td>
                    <td>
                        <a href="edit-email.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">↩️ Trả lời</a>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $row['id'] ?>)">🗑️ Xóa</button>
                    </td>
                </tr>
                <?php $stt++ ?>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: "Bạn có chắc chắn muốn xóa?",
        text: "Hành động này không thể hoàn tác!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Xóa",
        cancelButtonText: "Hủy"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "list-email.php?delete=" + id;
        }
    });
}
</script>

</body>
</html>
