<?php
include '../config/db.php';
include './includes/nav.php'; // Thêm menu điều hướng

// Kiểm tra ID danh mục
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>window.location.href='list-category.php'</script>";
    exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM categories WHERE id = $id");
$category = $result->fetch_assoc();

if (!$category) {
    echo "<script>alert('Danh mục không tồn tại!'); window.location.href='list-category.php';</script>";
    exit;
}

// Xử lý cập nhật danh mục
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        echo "<script>window.location.href='list-category.php'</script>";
    } else {
        echo "<script>showError();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa danh mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<div class="container mt-4">
    <div class="card shadow-lg p-4 mx-auto" style="max-width: 600px;">
        <h2 class="mb-4 text-center text-primary">✏️ Chỉnh sửa danh mục</h2>
        <form action="" method="post" onsubmit="return validateForm()">
            <div class="mb-3">
                <label class="form-label">📂 Tên danh mục</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($category['name']) ?>" required>
            </div>
            <button type="submit" class="btn btn-success w-100">✅ Cập nhật</button>
            <a href="list-category.php" class="btn btn-secondary w-100 mt-2">🔙 Quay lại</a>
        </form>
    </div>
</div>

<script>
function validateForm() {
    let name = document.getElementById("name").value.trim();
    if (name === "") {
        showError();
        return false;
    }
    return true;
}

function showError() {
    Swal.fire({
        icon: "error",
        title: "Lỗi!",
        text: "Vui lòng nhập tên danh mục.",
        confirmButtonColor: "#d33"
    });
}

function showSuccess() {
    Swal.fire({
        icon: "success",
        title: "Thành công!",
        text: "Danh mục đã được cập nhật.",
        confirmButtonColor: "#28a745"
    }).then(() => {
        window.location.href = "list-category.php";
    });
}
</script>

</body>
</html>
