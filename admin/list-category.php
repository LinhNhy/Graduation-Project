<?php
include '../config/db.php';
include './includes/nav.php'; // Thêm menu điều hướng

// Xóa danh mục
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM categories WHERE id = $id");
    echo "<script>window.location.href='list-category.php'</script>";
}

$result = $conn->query("SELECT * FROM categories ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-4 text-center">📂 Quản lý danh mục</h2>
    
    <div class="d-flex justify-content-center mb-3">
        <a href="add-category.php" class="btn btn-success">➕ Thêm danh mục</a>
    </div>

    <table class="table table-hover table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th width="10%">STT</th>
                <th>Tên danh mục</th>
                <th width="20%">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php $stt = 1; ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $stt ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td>
                        <a href="edit-category.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏️ Sửa</a>
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
            window.location.href = "list-category.php?delete=" + id;
        }
    });
}
</script>

</body>
</html>
