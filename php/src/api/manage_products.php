<?php
session_start();
require_once __DIR__ . "/../lib/util.php";

if (isset($_REQUEST["add_product"]) && isset($_GET["add"])) {
    $name = isset($_POST["name"]) ? $_POST["name"] : null;
    $detail = isset($_POST["detail"]) ? $_POST["detail"] : null;
    $size = isset($_POST["size"]) ? json_encode(array_map('trim', explode(',', $_POST["size"]))) : [];
    $price = isset($_POST["price"]) ? $_POST["price"] : null;
    $category_id = isset($_POST["category_products"]) ? $_POST["category_products"] : null;

    if (empty($name) || empty($detail) || empty($price) || empty($size) || empty($category_id)) {
        msg('กรุณากรอกข้อมูลให่ครบถ้วน!', 'warning', $_SERVER['HTTP_REFERER']);
    }

    $productIMG = $_FILES['productImage']['name'];
    $tmp_name = $_FILES['productImage']['tmp_name'];
    $path = '../assets/images/product_images/';

    if (empty($productIMG)) {
        msg('กรุณาเพิ่มรูปภาพสินค้าด้วย!', 'danger', $_SERVER['HTTP_REFERER']);
    } else {
        if (!empty($productIMG)) {
            $extension = pathinfo($productIMG, PATHINFO_EXTENSION);
            $productImage = uniqid() . '.' . $extension;
            if (empty($tmp_name)) {
                msg('ไฟล์รูปภาพมีปัญหา กรุณาลองใหม่อีกครั้ง!', 'danger', $_SERVER['HTTP_REFERER']);
            } else {
                move_uploaded_file($tmp_name, $path . $productImage);
            }
        } else {
            $productImage = 'image_placeholder.jpg';
        }
        $insert = sql("INSERT INTO `products` (`name`,`detail`,`size`,`price`,`cp_id`,`product_image`) VALUES(?,?,?,?,?,?)", [
            $name,
            $detail,
            $size,
            $price,
            $category_id,
            $productImage
        ]);
        if ($insert) {
            msg('เพิ่มรายการสินค้าเสร็จสิ้น!', 'success', $_SERVER['HTTP_REFERER']);
        }
    }
}

if (isset($_REQUEST["edit_product"]) && isset($_GET["edit"]) && isset($_GET["product_id"])) {
    $name = $_POST["name"];
    $detail = $_POST["detail"];
    $price = $_POST["price"];

    $product = $_FILES['productImage']['name'];
    $tmp_name = $_FILES['productImage']['tmp_name'];
    $path = '../assets/images/product_images/';

    $product_id = $_GET["product_id"];
    $row = sql("SELECT * FROM `products` WHERE `product_id` = ?", [$product_id])->fetch(PDO::FETCH_ASSOC);

    if (!empty($product)) {
        $extension = pathinfo($product, PATHINFO_EXTENSION);
        $productImage = uniqid() . '.' . $extension;
        if (empty($tmp_name)) {
            msg('ไฟล์รูปภาพมีปัญหา กรุณาลองใหม่อีกครั้ง!', 'danger', $_SERVER['HTTP_REFERER']);
        } else {
            if ($row["product_image"] != "image_placeholder.jpg") {
                unlink($path . $row["product_image"]);
            }
            move_uploaded_file($tmp_name, $path . $productImage);
        }
    } else {
        $productImage = $row["product_image"];
    }
    $insert = sql("UPDATE `products` SET `name` = ?, `detail` = ?, `price` = ?, `product_image` = ? WHERE `product_id` = ?", [
        $name,
        $detail,
        $price,
        $productImage,
        $product_id
    ]);
    if ($insert) {
        msg('แก้ไขรายการสินค้าเสร็จสิ้น!', 'success', $_SERVER['HTTP_REFERER']);
    }
}
if (isset($_REQUEST["delete_product"]) && isset($_GET["delete"]) && isset($_GET["product_id"])) {
    $path = '../assets/images/product_images/';

    $product_id = $_GET["product_id"];
    $row = sql("SELECT * FROM `products` WHERE `product_id` = ?", [$product_id])->fetch(PDO::FETCH_ASSOC);

    if ($row["product_image"] != "image_placeholder.jpg") {
        if (file_exists($path . $row["product_image"])) {
            unlink($path . $row["product_image"]);
        }
    }
    $insert = sql("DELETE FROM `products` WHERE `product_id` = ?", [
        $product_id
    ]);
    if ($insert) {
        msg('ลบรายการสินค้าเสร็จสิ้น!', 'success', $_SERVER['HTTP_REFERER']);
    }
}
