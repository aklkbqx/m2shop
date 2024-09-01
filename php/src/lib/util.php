<?php

// $servername = "sql110.infinityfree.com";
// $username = "if0_37138766";
// $password = "xlkynJvSDCjXP";
// $database = "if0_37138766_m2shop_db";
// $port = "9906";


$servername = "mysql-m2shop";
$username = "username";
$password = "password";
$database = "m2shop_db";
$port = "9906";

require_once $_SERVER['DOCUMENT_ROOT'] . "/config-function/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/config-function/function.php";

$webname = "M2SHOP";

$bannerTitle = [
    "M2SHOP",
    "ร้านขายเสื้อผ้ามือสองที่ดีที่สุด",
    "สินค้ามือสองสภาพดีเหมือนใหม่ ทั้งเสื้อ กางเกง รองเท้า"
];

$tel_var = "000-000-0000";
$email_var = "example@email.com";
$address_var = "193 ถนน มาตุลี ตำบลปากน้ำโพ อำเภอเมืองนครสวรรค์ นครสวรรค์ 60000";
$copyright_text_var = "© 2024 - All Rights Reserved";

$imageBanner = [
    pathImage("banner1.jpg", "banner"),
    pathImage("banner2.jpg", "banner"),
    pathImage("banner3.jpg", "banner")
];
