<?php
// Nếu nhận yêu cầu POST (trường hợp không mong muốn), dừng thực thi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exit;
}
?>
<footer class="padding-main">
    <div class="fot-content">
        <div class="fot1">
            <div class="fot-logo">
                <a href=""><img src="./image/logo.jpg" width="100px" alt=""></a>
            </div>
            <div class="fot-address">
                <p><i class="fa-solid fa-location-dot"></i> Địa chỉ: thôn Đông Sàng - xã <br> Đường Lâm - Sơn Tây - Hà Nội</p>
            </div>
            <div class="fot-time">
                <p class="fot-title"><i class="fa-solid fa-clock"></i> Giờ làm việc</p>
                <span>Thứ 2 đến Thứ 6: Từ 6:00 đến 17:00</span>
                <span>Thứ 7 & chủ nhật: Từ 6:00 đến 18:00</span>
            </div>
        </div>
        <div class="fot2">
            <div class="fot-title">Về chúng tôi</div>
            <p class="fot-sub-title">Thông tin liên hệ</p>
            <span><i class="fa-solid fa-phone"></i> 0983.577.324</span>
            <span><i class="fa-solid fa-envelope"></i> damthanhcv@gmail.com</span>
            <!-- <p class="fot-sub-title">Sản phẩm</p> -->
        </div>
    </div>
    <div class="copyright">
        <span>© 2025 Chuamia.com.vn.All Rights Reserved - Website developed by TrinhLinhNhi</span>
    </div>
    <div class="map">
        <div class="sub-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3720.772080295806!2d105.46751669999999!3d21.1614668!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3134f5bd11ccd161%3A0xfb9b75c53a44174e!2zQ2jDuWEgTcOtYQ!5e0!3m2!1svi!2s!4v1744785698115!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <div class="map-social">
                <a href=""><img src="./image/fb.png" width="40px" alt=""></a>
                <a href=""><img src="./image/ytb.png" width="40px" alt=""></a>
                <a href=""><img src="./image/ins.png" width="40px" alt=""></a>
            </div>
            <div onclick="window.location.href='https://maps.app.goo.gl/iB1MquV6fNgag9iTA'" class="review review1">
                <div class="star">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <h3>Đánh giá 1</h3>
                <p>
                Mia Pagoda is a unique ancient pagoda in Doai land. This place has traditional architecture.
                The ancient, mossy beauty will make you more curious to learn more about this temple.
                </p>
                <div class="user-review">
                    <img src="./image/rv1.png" width="40px" height="40px" alt="">
                    <div class="text-review">
                        <div class="review-name">Nhu Y Le Thi</div>
                        <p>6 months ago</p>
                    </div>
                </div>
            </div>
            <div onclick="window.location.href='https://maps.app.goo.gl/3gEyNTwmHVZc9vZf9'" class="review review2">
                <div class="star">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <h3>Đánh giá 2</h3>
                <p>
                Chùa Mía (Sùng Nghiêm tự, 崇嚴寺) là một ngôi chùa ở xã Đường Lâm, thị xã Sơn Tây. Chùa nằm gần làng cổ Đường Lâm, trong và xung quanh chùa bán đặc sản tương nếp...
                </p>
                <div class="user-review">
                    <img src="./image/rv2.png" width="40px" height="40px" alt="">
                    <div class="text-review">
                        <div class="review-name">Ha Hoang</div>
                        <p>10 months ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php
// Include file chat.php để hiển thị chat widget ngay dưới footer
include './chat.php';
?>
