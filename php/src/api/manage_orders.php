<?php
session_start();
require_once __DIR__ . "/../lib/util.php";

// Check if user is logged in
if (!isset($_SESSION["user_login"])) {
    msg("กรุณาทำการเข้าสู่ระบบก่อน", 'danger', "../pages/login.php");
    exit;
}

// Handle placing an order
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["placeOrder"])) {
    $totalPrice = isset($_POST["totalPrice"]) ? $_POST["totalPrice"] : "";
    $user_id = $_SESSION["user_login"];

    $carts = sql("SELECT * FROM `carts` WHERE `user_id` = ?", [$user_id])->fetchAll(PDO::FETCH_ASSOC);
    $response = [
        "totalPrice" => $totalPrice,
        "user_id" => $user_id,
        "carts" => $carts
    ];
    $oders_json = json_encode($response);

    $insert_order = sql("INSERT INTO `orders` (`oders_json`,`cart_id`, `user_id`) VALUES (?, ?, ?)", [$oders_json, $carts["item_id"], $user_id]);

    if ($insert_order) {
        sql("DELETE FROM `carts` WHERE `user_id` = ?", [$user_id]);
        msg("สั่งซื้อเรียบร้อย!", "success", "../pages/manage-orders.php");
    } else {
        msg("เกิดข้อผิดพลาดในการสั่งซื้อ", "danger", $_SERVER["HTTP_REFERER"]);
    }
    exit;
}

// Handle order cancellation
if (isset($_GET["cancelOrder"]) && isset($_GET["order_id"])) {
    $order_id = $_GET["order_id"];
    $update = sql("UPDATE `orders` SET `status` = ? WHERE `order_id` = ?", ["canceled", $order_id]);
    if ($update) {
        msg("ยกเลิกแล้ว!", "success", $_SERVER["HTTP_REFERER"]);
    } else {
        msg("เกิดข้อผิดพลาดในการยกเลิกคำสั่งซื้อ", "danger", $_SERVER["HTTP_REFERER"]);
    }
    exit;
}

// Handle order delivery
if (isset($_GET["deliveryOrder"]) && isset($_GET["order_id"])) {
    $order_id = $_GET["order_id"];

    $update = sql("UPDATE `orders` SET `status` = ? WHERE `order_id` = ?", ["delivering", $order_id]);

    if ($update) {
        msg("เริ่มจัดส่ง!", "success", $_SERVER["HTTP_REFERER"]);
    } else {
        msg("เกิดข้อผิดพลาดในการเริ่มจัดส่ง", "danger", $_SERVER["HTTP_REFERER"]);
    }
    exit;
}

// Handle order success
if (isset($_GET["successfullyOrder"]) && isset($_GET["order_id"])) {
    $order_id = $_GET["order_id"];

    $update = sql("UPDATE `orders` SET `status` = ? WHERE `order_id` = ?", ["successfully", $order_id]);

    if ($update) {
        msg("จัดส่งสำเร็จแล้ว!", "success", $_SERVER["HTTP_REFERER"]);
    } else {
        msg("เกิดข้อผิดพลาดในการจัดส่ง", "danger", $_SERVER["HTTP_REFERER"]);
    }
    exit;
}
