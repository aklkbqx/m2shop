<?php
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

$dataClothes = [
    [
        "imageURL" => "https://images.unsplash.com/photo-1516762689617-e1cffcef479d?q=80&w=3411&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
        "title" => "ชุดครบเซ็ท เสื้อ กางเกงยีน และรองเท้า",
        "price" => "790"
    ],
    [
        "imageURL" => "https://images.unsplash.com/photo-1564584217132-2271feaeb3c5?q=80&w=3540&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
        "title" => "เสื้อสีฟ้า",
        "price" => "199"
    ],
    [
        "imageURL" => "https://images.unsplash.com/photo-1510734790177-c931e3956547?q=80&w=3546&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
        "title" => "กางเกงยีน เนื้อแท้ใส่แล้วดูเท่ สำหรับผู้หญิง",
        "price" => "1500"
    ],
    [
        "imageURL" => "https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?q=80&w=3540&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
        "title" => "เสื้อสีดำลายกระดูกมือชูสองนิ้ว",
        "price" => "219"
    ],
    [
        "imageURL" => "https://images.unsplash.com/photo-1523381294911-8d3cead13475?q=80&w=3540&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
        "title" => "เสื้อยืด GRACECHAPEL สีเขียว oversize",
        "price" => "99"
    ],
    [
        "imageURL" => "https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?q=80&w=3314&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
        "title" => "เสื้อยืดแบรนด์ สีดำเนื้อผ้าดี ใส่สบาย",
        "price" => "150"
    ],
    [
        "imageURL" => "https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?q=80&w=3000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
        "title" => "เสื้อยิดสีขาว Free Size",
        "price" => "79"
    ],
];

$imageBanner = [
    pathImage("banner1.jpg", "banner"),
    pathImage("banner2.jpg", "banner"),
    pathImage("banner3.jpg", "banner")
];
