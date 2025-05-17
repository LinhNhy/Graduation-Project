<?php
include '../config/db.php';
include './includes/nav.php'; // Thêm menu điều hướng

// Lấy danh sách danh mục
$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']); // Lấy dữ liệu từ form
    $price = trim($_POST['price']);
    $category_id = $_POST['category_id'];
    $image = null;
    $unit = trim($_POST['unit']);
    $manual = trim($_POST['manual']);

    if (!empty($_FILES["image"]["name"])) {
        $image = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $image);
    }

    if (!empty($name) && !empty($description) && !empty($price) && !empty($category_id)) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, category_id, image, unit, manual) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdssss", $name, $description, $price, $category_id, $image, $unit, $manual);
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
    <title>Thêm sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<div class="container mt-4">
    <div class="card shadow-lg p-4 mx-auto" style="max-width: 700px;">
        <h2 class="mb-4 text-center text-primary">🛍️ Thêm sản phẩm</h2>
        <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="mb-3">
                <label class="form-label">📌 Tên sản phẩm</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Nhập tên sản phẩm..." required>
            </div>
            <div class="mb-3">
                <label class="form-label">📝 Mô tả sản phẩm</label>
                <textarea name="description" id="description" class="form-control" placeholder="Nhập mô tả sản phẩm..." required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">💰 Giá</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="Nhập giá sản phẩm..." required>
            </div>
            <div class="mb-3">
                <label class="form-label">📂 Danh mục</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php while ($cat = $categories->fetch_assoc()) : ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">🖼 Ảnh sản phẩm</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*" onchange="previewImage()">
                <div class="mt-2">
                    <img id="preview" src="" alt="Xem trước ảnh" class="img-fluid rounded" style="max-height: 200px; display: none;">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">📏 Đơn vị tính</label>
                <input type="text" name="unit" id="unit" class="form-control" placeholder="Ví dụ: cái, hộp, kg..." required>
            </div>
            <div class="mb-3">
                <label class="form-label">📖 Hướng dẫn sử dụng</label>
                <textarea name="manual" id="manual" class="form-control" placeholder="Nhập hướng dẫn sử dụng..." required></textarea>
            </div>
            <button type="submit" class="btn btn-success w-100">✅ Thêm sản phẩm</button>
            <a href="list-product.php" class="btn btn-secondary w-100 mt-2">🔙 Quay lại</a>
        </form>
    </div>
</div>

<script>
function validateForm() {
    let name = document.getElementById("name").value.trim();
    let description = document.getElementById("description").value.trim();
    let price = document.getElementById("price").value.trim();
    let category = document.getElementById("category_id").value.trim();
    let unit = document.getElementById("unit").value.trim();
    let manual = document.getElementById("manual").value.trim();

    if (name === "" || description === "" || price === "" || category === "" || unit === "" || manual === "") {
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

function previewImage() {
    let input = document.getElementById("image");
    let preview = document.getElementById("preview");
    
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

</body>
</html>