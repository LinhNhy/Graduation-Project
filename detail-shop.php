<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm</title>
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/index-reponsive.css">
    <link rel="stylesheet" href="./css/about.css">
    <link rel="stylesheet" href="./css/reponsive.css">
    <link rel="stylesheet" href="./css/blog.css">
    <link rel="stylesheet" href="./css/detail-blog.css">
    <link rel="stylesheet" href="./css/shop.css">
    <link rel="stylesheet" href="./css/detail-shop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        header.header-destop{
            padding: 100px 0px 20px 0px;
            /* background-image: url('./image/bg1.png');
            background-size: cover; */
        }
    </style>
</head>
<body>
    <?php

use BcMath\Number;

 include './includes/header.php' ?>
    <main>
        <?php $id = $_GET["id"] ?>
        <?php $data = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc() ?>
        <br>
        <div class="padding-main" style="background-color: white;">
            <div class="product-main">
                <div class="product">
                    <div>
                        <img width="100%" src="./admin/uploads/<?= $data['image'] ?>" alt="">
                    </div>
                    <div class="pro-detail">
                        <h2><?= $data['name'] ?></h2>
                        <div class="tag">
                            <?php $idCate = $data['category_id'] ?>
                            <?= $conn->query("SELECT * FROM categories WHERE id = $idCate")->fetch_assoc()['name'] ?>
                        </div>
                        <div class="price"><?= number_format($data['price']) ?> VNĐ / <?= $data['unit'] ?></div>
                        <!-- <form action="" method="POST">
                            <div>
                                <label for="">Size</label>
                                <select style="border-color: #F4F6FF" name="" id="">
                                    <option value="">Value</option>
                                    <option value="">Value</option>
                                    <option value="">Value</option>
                                </select>
                            </div>
                            <div>
                                <label for="">Số lượng</label>
                                <input value="1" type="number" style="padding:10px;border: 1.5px solid #F4F6FF;border-radius:5px">
                            </div>
                        </form> -->
                        <div class="action-pro">
                            <div><button class="btn-buynow">Liên hệ mua hàng</button></div>
                            <div class="act2">
                                <button onclick="window.location.href='https://zalo.me/0983577324'" class="btn-zalo"><img width="40px" src="./image/zalo.png" alt=""></button>
                                <button onclick="window.location.href='tel:0983577324'"><i class="fa-solid fa-phone"></i></button>
                            </div>
                        </div>
                        <div class="accordion">
                            <div class="accordion-item">
                                <button class="accordion-header">
                                    Hướng dẫn sử dụng <span><i class="fa-solid fa-angle-down"></i></span>
                                </button>
                                <div class="accordion-content">
                                    <?= $data['manual'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.querySelector(".accordion-header").addEventListener("click", function() {
                    const content = this.nextElementSibling;
                    const caret = this.querySelector("span");
                    
                    if (content.classList.contains("show")) {
                        content.style.maxHeight = "0px";
                        content.classList.remove("show");
                        caret.style.transform = "rotate(0deg)";
                    } else {
                        content.style.maxHeight = content.scrollHeight + "px";
                        content.classList.add("show");
                        caret.style.transform = "rotate(180deg)";
                    }
                });
            </script>
            <div class="tit-des-pro">
                <h3>Sản phẩm tương tự</h3>
                <p>Danh mục</p>
            </div>
            <div class="list-product">
                <?php 
                $proRelas = $conn->query("SELECT * FROM products WHERE category_id = $idCate");
                ?>
                <?php foreach($proRelas as $proRela) : ?>
                    <div class="a-product">
                        <img src="./admin/uploads/<?= $proRela['image'] ?>" alt="">
                        <div class="content-product">
                            <a href="./detail-shop.php?id=<?= $proRela['id'] ?>"><?= $proRela['name'] ?></a>
                            <p><?= $conn->query("SELECT * FROM categories WHERE id = $idCate")->fetch_assoc()['name'] ?></p>
                            <div class="price-product">Giá: <?= number_format($proRela['price']) ?> VNĐ</div>
                            <div class="act-product">
                                <button onclick="window.location.href = './detail-shop.php?id=<?= $proRela['id'] ?>'">Mua ngay</button>
                                <button><i class="fa-solid fa-cart-plus"></i></button>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
        <h1 style="text-align: center;margin-top: 50px;color: #3E5B3F;">ĐÁNH GIÁ</h1><br><br>
    </main>
    <?php include './includes/footer.php' ?>
    <script src="./js/index.js"></script>
</body>
</html>
</script>
