<?php
include '../config/db.php';
include './includes/nav.php'; // Thêm menu điều hướng

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $content = trim($_POST['content']);
    $image = null;
    $audio = null;

    // Xử lý ảnh
    if (!empty($_FILES["image"]["name"])) {
        $image = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $image);
    }

    // Xử lý audio
    if (!empty($_FILES["audio"]["name"])) {
        $audio = time() . "_" . basename($_FILES["audio"]["name"]);
        move_uploaded_file($_FILES["audio"]["tmp_name"], "uploads/" . $audio);
    }

    // Kiểm tra các trường bắt buộc và lưu vào database
    if (!empty($title) && !empty($description) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO posts (title, description, content, image, audio) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $description, $content, $image, $audio);
        $stmt->execute();
        echo "<script>window.location.href='list-post.php'</script>";
    } else {
        echo "<script>showError();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm bài viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<div class="container mt-4">
    <div class="card shadow-lg p-4 mx-auto" style="max-width: 700px;">
        <h2 class="mb-4 text-center text-primary">📝 Thêm bài viết</h2>
        <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="mb-3">
                <label class="form-label">📌 Tiêu đề</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Nhập tiêu đề bài viết..." required>
            </div>
            <div class="mb-3">
                <label class="form-label">📄 Mô tả</label>
                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Nhập mô tả ngắn..." required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">✍ Nội dung</label>
                <textarea name="content" id="content" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">🖼 Ảnh bài viết</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*" onchange="previewImage()">
                <div class="mt-2">
                    <img id="preview" src="" alt="Xem trước ảnh" class="img-fluid rounded" style="max-height: 200px; display: none;">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">🎶 Audio bài viết</label>
                <input type="file" name="audio" id="audio" class="form-control" accept="audio/*">
            </div>
            <button type="submit" class="btn btn-success w-100">✅ Thêm bài viết</button>
            <a href="list-post.php" class="btn btn-secondary w-100 mt-2">🔙 Quay lại</a>
        </form>
    </div>
</div>

<script>
CKEDITOR.replace('content');

function validateForm() {
    let title = document.getElementById("title").value.trim();
    let description = document.getElementById("description").value.trim();
    let content = CKEDITOR.instances.content.getData().trim();

    if (title === "" || description === "" || content === "") {
        showError();
        return false;
    }
    return true;
}

function showError() {
    Swal.fire({
        icon: "error",
        title: "Lỗi!",
        text: "Vui lòng nhập đầy đủ thông tin bài viết.",
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
