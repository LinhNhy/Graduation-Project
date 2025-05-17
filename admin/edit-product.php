<?php
include '../config/db.php';
include './includes/nav.php';

// Kiểm tra ID sản phẩm
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list-product.php");
    exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE id = $id");
$product = $result->fetch_assoc();

if (!$product) {
    echo "<script>alert('Sản phẩm không tồn tại!'); window.location.href='list-product.php';</script>";
    exit;
}

// Lấy danh sách danh mục
$categories = $conn->query("SELECT * FROM categories");

// Xử lý cập nhật sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $category_id = trim($_POST['category_id']);
    $description = trim($_POST['description']);
    $image = $product['image'];
    $unit = trim($_POST['unit']);
    $manual = trim($_POST['manual']);


    if (!empty($_FILES["image"]["name"])) {
        $image = time() . "_" . basename($_FILES["image"]["name"]);
        $target_dir = "uploads/";
        $target_file = $target_dir . $image;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            if (!empty($product['image']) && file_exists("uploads/" . $product['image'])) {
                unlink("uploads/" . $product['image']);
            }
        }
    }

    if (!empty($name) && !empty($price) && !empty($category_id)) {
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, category_id = ?, description = ?, image = ?, unit = ?, manual = ? WHERE id = ?");
        $stmt->bind_param("siissssi", $name, $price, $category_id, $description, $image, $unit, $manual, $id);
        
        $stmt->execute();
        echo "<script>window.location.href='list-product.php'</script>";
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
    <title>Chỉnh sửa sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<div class="container mt-4">
    <div class="card shadow-lg p-4 mx-auto" style="max-width: 800px;">
        <h2 class="mb-4 text-center text-primary">🛍️ Chỉnh sửa sản phẩm</h2>
        <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="mb-3">
                <label class="form-label">📌 Tên sản phẩm</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">💰 Giá sản phẩm</label>
                <input type="number" name="price" id="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">📂 Danh mục</label>
                <select name="category_id" id="category_id" class="form-control select2" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php while ($category = $categories->fetch_assoc()): ?>
                        <option value="<?= $category['id'] ?>" <?= ($product['category_id'] == $category['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">📝 Mô tả sản phẩm</label>
                <textarea name="description" id="description" class="form-control" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">🖼 Ảnh hiện tại</label><br>
                <?php if (!empty($product['image'])): ?>
                    <img src="uploads/<?= $product['image'] ?>" width="150" class="img-thumbnail mb-2">
                <?php else: ?>
                    <p>⚠️ Chưa có ảnh</p>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">📸 Chọn ảnh mới (nếu muốn thay đổi)</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">📏 Đơn vị tính</label>
                <input type="text" name="unit" id="unit" class="form-control" value="<?= htmlspecialchars($product['unit']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">📖 Hướng dẫn sử dụng</label>
                <textarea name="manual" id="manual" class="form-control" rows="4" required><?= htmlspecialchars($product['manual']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-success w-100">✅ Cập nhật</button>
            <a href="list-product.php" class="btn btn-secondary w-100 mt-2">🔙 Quay lại</a>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });

    function validateForm() {
        let name = document.getElementById("name").value.trim();
        let price = document.getElementById("price").value.trim();
        let category_id = document.getElementById("category_id").value.trim();
        let description = document.getElementById("description").value.trim();
        let unit = document.getElementById("unit").value.trim();
let manual = document.getElementById("manual").value.trim();

if (name === "" || price === "" || category_id === "" || description === "" || unit === "" || manual === "") {
    showError();
    return false;
}

        return true;
    }

    function showError() {
        Swal.fire({
            icon: "error",
            title: "Lỗi!",
            text: "Vui lòng nhập đầy đủ thông tin sản phẩm.",
            confirmButtonColor: "#d33"
        });
    }
</script>

</body>
</html>