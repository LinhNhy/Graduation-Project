<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'trinhlinhnhi.work@gmail.com';
$mail->Password = 'lfpx ijpt zkwe yzoj';
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;

$mail->setFrom('trinhlinhnhi.work@gmail.com');
$mail->addAddress("niboss458@gmail.com");
$mail->isHTML(true);
$mail->Subject = "Chùa Mía";
$mail->Body = "Chào anh nghĩa nha";

if ($mail->send()) {
    return true;
} else {
    return false;
}
?>

