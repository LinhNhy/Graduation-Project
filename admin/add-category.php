<?php
include '../config/db.php';
include './includes/nav.php'; // Thêm menu điều hướng

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
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
    <title>Thêm danh mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
</head>
<body>

<div class="container mt-4">
    <div class="card shadow-lg p-4 mx-auto" style="max-width: 500px;">
        <h2 class="mb-4 text-center text-primary">➕ Thêm danh mục</h2>
        <form action="" method="post" onsubmit="return validateForm()">
            <div class="mb-3">
                <label class="form-label">Tên danh mục</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Nhập tên danh mục..." required>
            </div>
            <button type="submit" class="btn btn-success w-100">✅ Thêm danh mục</button>
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
        text: "Tên danh mục không được để trống.",
        confirmButtonColor: "#d33"
    });
}
</script>

</body>
</html>
