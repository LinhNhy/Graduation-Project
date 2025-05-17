<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        /* Tăng chiều ngang của container dashboard-container */
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<?php include './includes/nav.php';?>

<!-- Nội dung trang quản trị -->
<div class="container dashboard-container">
    <h2 class="text-center mb-4">Bảng điều khiển</h2>
    <div class="row">
        <div class="col-md-3 mb-3">
            <a href="list-category.php" class="btn btn-primary btn-custom d-block text-center">📂 Quản lý danh mục</a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="list-product.php" class="btn btn-success btn-custom d-block text-center">🛒 Quản lý sản phẩm</a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="list-post.php" class="btn btn-warning btn-custom d-block text-center">📝 Quản lý bài viết</a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="list-chatbot.php" class="btn btn-info btn-custom d-block text-center">🤖 Quản lý chatbot</a>
        </div>
    </div>
    <img style="margin-top: 20px; border-radius: 5px; height: 100%; width: 100%;" src="./images/banner.png" alt="Banner">
</div>

<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>
