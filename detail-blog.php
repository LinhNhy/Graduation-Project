<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết bài viết</title>
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/index-reponsive.css">
    <link rel="stylesheet" href="./css/about.css">
    <link rel="stylesheet" href="./css/reponsive.css">
    <link rel="stylesheet" href="./css/blog.css">
    <link rel="stylesheet" href="./css/detail-blog.css">
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
    <?php include './includes/header.php' ?>
    <main>
        <?php 
        $id = $_GET["id"];
        $data = $conn->query("SELECT * FROM posts WHERE id = $id")->fetch_assoc();
        ?>
        <br>
        <div class="padding-main">
            <div class="content-blog-view" style="background-color: white;padding: 20px;">
                <h1 style="color: #3E5B3F;"><?= $data['title'] ?></h1>
                <div style="margin: 20px 0px;display:flex; align-items: center;gap:20px">
                    <span style="color:red; font-weight: bold;">Trình phát </span>
                    <audio src="./admin/uploads/<?= $data['audio'] ?>" loop controls></audio>
                </div>
                <div class="time-up" style="color:gray;padding-top:10px;"><?= date('d-m-Y', strtotime($data['created_at'])) ?></div>
                <div class="des-heading" style="font-size: 13px;padding-top:10px;"><?= $data['description'] ?></div>
                <img src="./admin/uploads/<?= $data['image'] ?>" width="100%" style="margin-top: 10px;" alt="">
                <div class="content-blog-main">
                    <?= $data['content'] ?>
                </div>
            </div>
            <div class="title-new" style="font-weight: normal;border-bottom: none;text-align: center;width: 100%;margin-top: 50px;"><h2>DANH MỤC THAM KHẢO</h2></div>
            <div class="list-blog">
                <?php 
                $blogs = $conn->query("SELECT * FROM posts LIMIT 3");
                ?>
                <?php foreach ($blogs as $blog) : ?>
                    <div class="a-blog">
                        <img src="./admin/uploads/<?= $blog['image'] ?>" alt="">
                        <div class="infor-a-blog">
                            <div style="cursor:pointer" onclick="window.location.href = './detail-blog.php?id=<?= $blog['id'] ?>'" class="title-blog"><?= $blog['title'] ?></div>
                            <div class="des-blog">
                                <?= $blog['description'] ?>
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
    let index = 0;
    const slides = document.querySelectorAll('.slide');
    const slider = document.querySelector('.slider');
    const indicators = document.querySelector('.indicators');

    function showSlide() {
        slider.style.transform = `translateX(${-index * 100}%)`;
        updateIndicators();
    }

    function nextSlide() {
        index = (index + 1) % slides.length;
        showSlide();
    }

    function updateIndicators() {
        document.querySelectorAll('.indicator').forEach((indicator, i) => {
            indicator.classList.toggle('active', i === index);
        });
    }

    function createIndicators() {
        for (let i = 0; i < slides.length; i++) {
            const indicator = document.createElement('div');
            indicator.classList.add('indicator');
            indicator.addEventListener('click', () => {
                index = i;
                showSlide();
            });
            indicators.appendChild(indicator);
        }
        updateIndicators();
    }

    createIndicators();
    setInterval(nextSlide, 3000);
});
</script>
<style>
    #bgAudio{
        display: none !important;
    }
</style>