<?php
session_start();
require_once __DIR__ . "/../lib/util.php";
$user_id = isset($_SESSION["user_login"]) ? $_SESSION["user_login"] : (isset($_SESSION["admin_login"]) ? header("location: ../admin/") : null);
if ($user_id) {
    $row = sql("SELECT * FROM `users` WHERE `user_id` = ?", [$user_id])->fetch(PDO::FETCH_ASSOC);
}
$product_id = isset($_GET["product_id"]) && $_GET["product_id"] ? $_GET["product_id"] : header("location: /");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้า | Carts</title>
    <?php require_once __DIR__ . "/../lib/link.php"; ?>
</head>

<body class="bg-primary">
    <?php require_once __DIR__ . "/../components/navbar.php"; ?>

    <div class="p-2 mb-4">
        <div class="container bg-white rounded-5 shadow p-3" style="margin-top: 8rem;">
            <a href="javascript:history.back()" class="text-primary fs-5">
                <i class="fa-solid fa-arrow-left"></i>
                กลับ
            </a>

            <?php $product = sql("SELECT * FROM `products` WHERE `product_id` = ?", [$product_id])->fetch(PDO::FETCH_ASSOC); ?>
            <div class="row mt-4 pb-5">
                <div class="col-12 col-sm-5 d-flex justify-content-center">
                    <img src="<?= pathImage($product["product_image"], "product_images") ?>" width="400px" height="350px" class="border object-fit-cover rounded-2">
                </div>
                <div class="col-12 col-sm-7">
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex flex-column">
                            <h3><?= $product["name"] ?></h3>
                            <h6><?= $product["detail"] ?></h6>
                        </div>
                        <div class="bg-light p-4 rounded-4" style="width: 200px;">
                            <h4 class="text-primary fw-semibold">ราคา​ ฿<span><?= $product["price"] ?></span></h4>
                        </div>
                        <div class="d-flex flex-column">
                            <h5>ขนาด:</h5>
                            <div class="d-flex flex-row gap-2">
                                <?php foreach (json_decode($product["size"]) as $index => $size) { ?>
                                    <input type="radio" name="size" id="size-<?= $size . $index; ?>" hidden>
                                    <label for="size-<?= $size . $index; ?>" class="btn btn-outline-primary rounded-4 check-size" style="width: 100px;"><?= $size; ?></label>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <h6>จำนวน</h6>
                            <div class="d-flex overflow-hidden border rounded-4" style="width: 110px;">
                                <button type="button" class="btn btn-primary border-0 rounded-0 border-0" onclick="minusAmountItem(<?= $i; ?>)">-</button>
                                <input oninput="changeAmountItem(<?= $i; ?>)" data-amountitem="<?= $i; ?>" type="number" class="form-control border-0 text-center" style="width: 50px;box-shadow: none !important;outline: none;" value="1">
                                <button type="button" class="btn btn-primary border-0 rounded-0 border-0" onclick="plusAmountItem(<?= $i; ?>)">+</button>
                            </div>
                            <button type="button" class="btn btn-primary rounded-5 fs-5 ms-auto me-5 py-2 px-4">
                                <i class="fa-solid fa-cart-shopping"></i> เพิ่มลงตะกร้าสินค้า
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('input[type="radio"][name="size"]').change(function() {
                $('.check-size').removeClass('active-size');
                $(this).next('.check-size').addClass('active-size');
            });
            const updateTotalPrice = () => {
                const totalPrice = $("#total-price");
                const elementTotalItem = $("[data-total-eachitem]");
                let item_price = [];
                elementTotalItem.each((index, item) => {
                    item_price.push(parseInt($(item).text()))
                })
                const sum = item_price.reduce((accumulator, current) => accumulator + current);
                totalPrice.text(sum)
            }

            const plusAmountItem = (item_id) => {
                const eachItem = $(`[data-eachitem=${item_id}]`)
                const amountItem = $(`[data-amountitem=${item_id}]`)
                const totalItem = $(`[data-total-eachitem=${item_id}]`)

                const plus = amountItem.val(parseInt(amountItem.val()) + 1)
                totalItem.text(plus.val() * parseInt(eachItem.text()))
                updateTotalPrice()
            }
            const minusAmountItem = (item_id) => {
                const eachItem = $(`[data-eachitem=${item_id}]`)
                const amountItem = $(`[data-amountitem=${item_id}]`)
                const totalItem = $(`[data-total-eachitem=${item_id}]`)

                if (parseInt(amountItem.val()) !== 1) {
                    const minus = amountItem.val(parseInt(amountItem.val()) - 1);
                    totalItem.text(minus.val() * parseInt(eachItem.text()))
                }
                updateTotalPrice()
            }

            const changeAmountItem = (item_id) => {
                const eachItem = $(`[data-eachitem=${item_id}]`)
                const amountItem = $(`[data-amountitem=${item_id}]`)
                const totalItem = $(`[data-total-eachitem=${item_id}]`)

                if (amountItem.val() == 0) {
                    amountItem.val(1)
                } else {
                    totalItem.text(parseInt(eachItem.text()) * parseInt(amountItem.val()));
                }
                updateTotalPrice()
            }

            updateTotalPrice()
        });
    </script>
</body>

</html>