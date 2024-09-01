<?php
session_start();
require_once __DIR__ . "/../lib/util.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET["addCategory"])) {
    $res = [];
    $category_name = isset($_POST["category_name"]) ? $_POST["category_name"] : null;
    if (empty($category_name)) {
        $res = ["success" => false, "message" => "กรุณาพิมพ์เพิ่มหมวดหมู่!"];
    } else {
        $insert = sql("INSERT INTO `category_products` (`name`) VALUES (?)", [$category_name]);
        if ($insert) {
            $res = ["success" => true, "message" => "เพิ่มสำเร็จแล้ว!"];
        }
    }
    echo json_encode($res);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET["deleteCategory"])) {
    $res = [];
    $cp_id = isset($_POST["cp_id"]) ? $_POST["cp_id"] : null;
    if (empty($cp_id)) {
        $res = ["success" => false, "message" => "เกิดข้อผิดพลาด ไม่สามารถลบได้!"];
    } else {
        $deleted = sql("DELETE FROM `category_products` WHERE `cp_id` = ?", [$cp_id]);
        if ($deleted) {
            $res = ["success" => true, "message" => "ลบหมวดหมู่สำเร็จแล้ว!"];
        }
    }
    echo json_encode($res);
}
