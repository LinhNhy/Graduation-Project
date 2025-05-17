<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/index-reponsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link rel="shortcut icon" href="./image/logo.jpg" type="image/x-icon">

    <style>
        nav{
            background: transparent !important;
        }
        .search input{
            background: #3E5B3F;
        }
        .search i{
            color: white;
        }
        header.header-destop{
            background-image: url('./image/bg1.png');
            background-size: cover;
        }
        header.header-destop{
            padding: 30px 0px 680px 0px;
        }
        .search button{
            background: #3E5B3F !important;
            border: none;
        }
    </style>
</head>
<body>
    <?php include './includes/header.php' ?>
    <main>
        <div class="padding-main">
            <h1 class="title">Lời giới thiệu</h1>
            <span class="sub-title">Đôi lời về</span>
            <div class="text-about">
                <p>
                    Chùa Mía (tên chữ: Sùng Nghiêm tự, 崇嚴寺) là một ngôi chùa ở xã Đường Lâm, thị xã Sơn Tây, thành phố Hà Nội. Xưa kia, vùng này là Cam Giá, tên Nôm là Mía, nên chùa này được quen gọi là chùa Mía. Đây là ngôi chùa lưu giữ nhiều tượng nghệ thuật nhất Việt Nam (287 tượng).Ban đầu, chùa có quy mô nhỏ, được xây dựng từ xa xưa. Năm 1632, Phi tần trong phủ chúa Trịnh Tráng là Ngô Thị Ngọc Diệu (còn có tên là Nguyễn Thị Ngọc Dong)  thấy miếu bị hoang phế điêu tàn nên đã cùng cha mẹ và người dân các làng thuộc tổng Cam Giá (tức Tổng Mía) cùng nhau tôn tạo lại. Phi tần Ngọc Dong vốn là người làng Nam Nguyễn (Nam An) trong Tổng Mía, nên được người mến mộ gọi là "Bà Chúa Mía", đồng thời đã tạc tượng đưa vào phối thờ ở chùa và còn có đền thờ riêng. Về sau chùa được tu bổ nhiều lần, song đến nay quy mô tôn tạo thời Bà chúa Mía dường như vẫn được bảo tồn nguyên vẹn.
                </p>
            </div>
        </div>
        <img src="./image/bg2.png" width="100%" height="100%" alt="">
        <h1 style="text-align: center;margin-top: 50px;color: #3E5B3F;">THÔNG TIN</h1>
        <div class="padding-main">
            <div class="carousel-container">
                <?php
                include './config/db.php';
                $infors = $conn->query("SELECT * FROM posts LIMIT 9");
                ?>
                <div class="infor-list">
                    <?php foreach($infors as $infor) : ?>
                        <div class="infor" style="cursor:pointer" onclick="window.location.href = './detail-blog.php?stop=1&id=<?= $infor['id'] ?>'">
                            <h2><?= $infor['title'] ?></h2>
                            <p><?= $infor['description'] ?></p>
                            <img src="./admin/uploads/<?= $infor['image'] ?>" width="100%" alt="">
                        </div>
                    <?php endforeach ?>
                </div>
                <div class="dots"></div>
            </div>
        </div>
        <div class="padding-main pro-main" style="background: #3E5B3F; color: white">
            <h1>SẢN PHẨM NỔI BẬT</h1>
            <div class="pro-list">
                <?php 
                include './config/db.php';
                $products = $conn->query("SELECT * FROM products LIMIT 6");
                ?>
                <?php foreach ($products as $product) : ?>
                    <div class="pro">
                        <img src="./admin/uploads/<?= $product['image'] ?>" alt="">
                        <div class="text-pro">
                            <h3><?= $product['name'] ?></h3>
                            <p><?= $product['description'] ?></p>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            <div class="see-more-a">
                <a href="./shop.php">XEM THÊM</a>
            </div>
        </div>
        <h1 style="text-align: center;margin-top: 50px;color: #3E5B3F;">ĐÁNH GIÁ</h1>
    </main>
    <?php include './includes/footer.php' ?>
    <script src="./js/index.js"></script>
</body>
</html>
