<?php
include '../config/db.php';
include './includes/nav.php'; // Thêm menu điều hướng

// Xóa sản phẩm
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id = $id");
    echo "<script>window.location.href='list-product.php'</script>";
}

// Lấy danh sách sản phẩm
$result = $conn->query("SELECT products.*, categories.name AS category_name 
                        FROM products 
                        JOIN categories ON products.category_id = categories.id 
                        ORDER BY products.created_at DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-4 text-center">🛒 Quản lý sản phẩm</h2>

    <div class="d-flex mb-3 justify-content-center">
        <a href="add-product.php" class="btn btn-success">➕ Thêm sản phẩm</a>
    </div>

    <table class="table table-hover table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th width="5%">STT</th>
                <th width="10%">Ảnh</th>
                <th width="25%">Tên sản phẩm</th>
                <th width="15%">Giá</th>
                <th width="20%">Danh mục</th>
                <th width="15%">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php $stt = 1; ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $stt ?></td>
                    <td>
                        <img src="uploads/<?= $row['image'] ?>" alt="Ảnh" class="img-thumbnail" width="60">
                    </td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><strong><?= number_format($row['price'], 0) ?> VND</strong></td>
                    <td><?= htmlspecialchars($row['category_name']) ?></td>
                    <td>
                        <a href="edit-product.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏️ Sửa</a>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $row['id'] ?>)">🗑️ Xóa</button>
                    </td>
                </tr>
                <?php $stt++; ?>
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
            window.location.href = "list-product.php?delete=" + id;
        }
    });
}
</script>

</body>
</html>
