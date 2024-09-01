<?php
session_start();
require_once __DIR__ . "/../lib/util.php";
$user_id = isset($_SESSION["user_login"]) ? $_SESSION["user_login"] : (isset($_SESSION["admin_login"]) ? header("location: ../admin/") : header("location: /"));
if ($user_id) {
    $row = sql("SELECT * FROM `users` WHERE `user_id` = ?", [$user_id])->fetch(PDO::FETCH_ASSOC);
}
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

    <div class="p-2">
        <div class="container bg-white rounded-5 shadow p-3" style="margin-top: 7rem;">
            <?php $carts = sql("SELECT `item_id`,`product_id`,`amount` ,`size` FROM `carts` WHERE `user_id` = ?", [$row["user_id"]]);
            if ($carts->rowCount() > 0) {
            ?>
                <div class="row">
                    <div class="col-xxl-7">
                        <div class="">
                            <a href="javascript:history.back()" class="text-primary fs-5">
                                <i class="fa-solid fa-arrow-left"></i>
                                กลับ
                            </a>
                            <h1>ตะกร้าของฉัน</h1>
                            <div class="d-flex justify-content-between fs-5">
                                <div>สินค้าในตะกร้า</div>
                                <div><?= $countCarts ?> รายการ</div>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-2 mt-2 overflow-auto overflow-x-hidden pb-2 position-relative" style="height: 685px;" id="scrollProducts">
                            <div id="shadow-scroll" class="border-bottom position-sticky z-1 bg-white top-0 opacity-0" style="padding: 0.1rem;box-shadow: 0px 0px 20px 0px #626262;"></div>
                            <?php
                            while ($cart = $carts->fetch(PDO::FETCH_ASSOC)) {
                                $products = sql("SELECT * FROM `products` WHERE `product_id` = ?", [$cart["product_id"]]);
                                while ($product = $products->fetch(PDO::FETCH_ASSOC)) { ?>
                                    <div class="border rounded-4 p-2">
                                        <div class="row">
                                            <div class="col-sm-5 col-12 d-flex align-items-center">
                                                <div class="d-flex align-items-center gap-4">
                                                    <img src="<?= pathImage($product["product_image"], "product_images") ?>" width="100px" height="100px" class="border rounded-4 object-fit-cover">
                                                    <div class="d-flex flex-column gap-2">
                                                        <h6 class="fw-regular"><?= $product["name"] ?></h6>
                                                        <h6><span class="fw-bold">ไซส์:</span> <?= $cart["size"] ?></h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-7 col-12 d-flex align-items-center justify-content-start mt-4 mt-sm-0">
                                                <div class="w-100">
                                                    <div class="row">
                                                        <div class="col-sm-2 col-4 p-0 d-flex justify-content-center">
                                                            <div>ชิ้นละ</div>
                                                        </div>
                                                        <div class="col-sm-5 col-4 p-0 d-flex justify-content-center">
                                                            <div>จำนวน</div>
                                                        </div>
                                                        <div class="col-sm-2 col-4 p-0 d-flex justify-content-center">
                                                            <div>ราคารวม</div>
                                                        </div>
                                                        <div class="col-sm-3 col-2 p-0 d-flex justify-content-center"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-2 col-4 p-0 d-flex align-items-center justify-content-center">
                                                            <h6>
                                                                <span data-eachitem="<?= $cart["item_id"]; ?>"><?= $product["price"]; ?></span> บาท
                                                            </h6>
                                                        </div>
                                                        <div class="col-sm-5 col-4 p-0 d-flex align-items-center justify-content-center">
                                                            <div class="d-flex overflow-hidden border rounded-4" style="width: 110px;">
                                                                <button type="button" class="btn btn-light border-0 rounded-0 border-0" onclick="minusAmountItem(<?= $cart['item_id']; ?>)">-</button>
                                                                <input oninput="changeAmountItem(<?= $cart['item_id']; ?>)" data-amountitem="<?= $cart['item_id']; ?>" type="number" class="form-control border-0 text-center" style="width: 50px;box-shadow: none !important;outline: none;" value="<?= $cart["amount"] ?>">
                                                                <button type="button" class="btn btn-light border-0 rounded-0 border-0" onclick="plusAmountItem(<?= $cart['item_id']; ?>)">+</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2 col-4 p-0 d-flex align-items-center justify-content-center">
                                                            <div style="font-size: 14px;">
                                                                <span data-total-eachitem="<?= $cart['item_id']; ?>"><?= formatNumberWithComma($product["price"] * $cart["amount"]) ?></span> บาท
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 col-12 p-0 d-flex justify-content-sm-center justify-content-end">
                                                            <button onclick="deleteItem(<?= $cart['item_id']; ?>)" type="button" class="btn btn-danger rounded-4 me-2 me-sm-0">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            } ?>
                        </div>
                    </div>
                    <div class="col-xxl-5 col-12 d-flex justify-content-start flex-column gap-5 bg-light p-4 rounded-5">
                        <form action="../api/manage_orders.php?placeOrder" method="post">
                            <h2 class="mb-2 text-primary">สรุปรายการสั่งซื้อ</h2>
                            <div class="d-flex justify-content-between fs-4">
                                <div class="fw-bold">
                                    จำนวนสินค้าทั้งหมด
                                </div>
                                <div>
                                    <span id="totalAmountItem"><?= formatNumberWithComma($countCarts) ?></span> ชิ้น
                                </div>
                            </div>
                            <div class="d-flex justify-content-between fs-4">
                                <div class="fw-bold">
                                    ราคาทั้งหมด
                                </div>
                                <div>
                                    <span id="total-price"></span> บาท
                                    <input hidden type="text" id="total-price-input" name="totalPrice">
                                </div>
                            </div>
                            <button name="placeOrder" type="submit" class="btn btn-primary w-100 rounded-5 mt-2 fs-3 fw-bold">สั่งซื้อ</button>
                        </form>
                    </div>
                </div>
            <?php } else { ?>
                <div class="pb-5">
                    <a href="javascript:history.back()" class="text-primary fs-5">
                        <i class="fa-solid fa-arrow-left"></i>
                        กลับ
                    </a>
                    <h1>ตะกร้าของฉัน</h1>
                    <div class="fs-5">สินค้าในตะกร้า</div>
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <img src="../assets/images/web/cartisempty.png" height="400px">
                        <h4 class="text-muted">ยังไม่มีสินค้าในตะกร้าของคุณ <a href="/">เริ่มสั่งสินค้าเลย!</a></h4>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>


    <script>
        const scrollProducts = $("#scrollProducts");

        scrollProducts.on('scroll', function() {
            const shadowScroll = $("#shadow-scroll");
            if (scrollProducts.scrollTop() > 10) {
                shadowScroll.removeClass("opacity-0").addClass("opacity-1");
            } else {
                shadowScroll.removeClass("opacity-1").addClass("opacity-0");
            }
        });

        const updateCartItem = (item_id, amount) => {
            $.ajax({
                url: '../api/manage_carts.php?updateAmount',
                type: 'POST',
                data: {
                    item_id: item_id,
                    amount: amount,
                },
                success: (response) => {
                    updateTotalPrice();
                },
                error: (xhr, status, error) => {
                    console.error('Error updating cart:', error);
                }
            });
        };

        const updateTotalPrice = () => {
            const totalPrice = $("#total-price");
            const totalPriceInput = $("#total-price-input");
            const totalAmountItem = $("#totalAmountItem");
            const elementTotalItem = $("[data-total-eachitem]");
            const elementAmountitem = $("[data-amountitem]");
            let item_price = [];
            elementTotalItem.each((index, item) => {
                const numberWithoutCommas = $(item).text().split(",").join("");
                item_price.push(parseInt(numberWithoutCommas));
            });

            const sum = item_price.reduce((accumulator, current) => accumulator + current, 0);
            totalPrice.text(formatNumberWithComma(sum));
            totalPriceInput.val(sum);

            let item_price2 = [];
            elementAmountitem.each((index, item) => {
                item_price2.push(parseInt($(item).val()));
            });
            const sum2 = item_price2.reduce((accumulator, current) => accumulator + current, 0);
            totalAmountItem.text(sum2);
        };

        const plusAmountItem = (item_id) => {
            const eachItem = $(`[data-eachitem=${item_id}]`);
            const amountItem = $(`[data-amountitem=${item_id}]`);
            const totalItem = $(`[data-total-eachitem=${item_id}]`);
            const size = $(`[data-size=${item_id}]`).val(); // เพิ่มการดึงขนาด

            const newAmount = parseInt(amountItem.val()) + 1;
            amountItem.val(newAmount);
            totalItem.text(formatNumberWithComma(newAmount * parseInt(eachItem.text())));
            updateTotalPrice();

            updateCartItem(item_id, newAmount, size);
        };

        const minusAmountItem = (item_id) => {
            const eachItem = $(`[data-eachitem=${item_id}]`);
            const amountItem = $(`[data-amountitem=${item_id}]`);
            const totalItem = $(`[data-total-eachitem=${item_id}]`);
            const size = $(`[data-size=${item_id}]`).val(); // เพิ่มการดึงขนาด

            if (parseInt(amountItem.val()) > 1) {
                const newAmount = parseInt(amountItem.val()) - 1;
                amountItem.val(newAmount);
                totalItem.text(formatNumberWithComma(newAmount * parseInt(eachItem.text())));
                updateTotalPrice();

                updateCartItem(item_id, newAmount, size);
            }
        };

        const changeAmountItem = (item_id) => {
            const eachItem = $(`[data-eachitem=${item_id}]`);
            const amountItem = $(`[data-amountitem=${item_id}]`);
            const totalItem = $(`[data-total-eachitem=${item_id}]`);
            const size = $(`[data-size=${item_id}]`).val(); // เพิ่มการดึงขนาด

            const newAmount = Math.max(1, parseInt(amountItem.val()));
            amountItem.val(newAmount);
            totalItem.text(formatNumberWithComma(newAmount * parseInt(eachItem.text())));
            updateTotalPrice();

            updateCartItem(item_id, newAmount, size);
        };
        const deleteItem = (item_id) => {
            Swal.fire({
                icon: 'warning',
                title: 'แน่ใจไหม​!',
                text: "ต้องการที่จะลบสินค้านี้ออกจากตะกร้าใช่หรือไม่",
                showConfirmButton: false,
                showCancelButton: true,
                showDenyButton: true,
                reverseButtons: true,
                denyButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก',
                customClass: {
                    popup: 'border-radius-sweetAlert',
                    confirmButton: 'border-radius-sweetAlert',
                    cancelButton: 'border-radius-sweetAlert',
                    denyButton: 'border-radius-sweetAlert'
                }
            }).then((result) => {
                if (result.isDenied) {
                    $.ajax({
                        url: `../api/manage_carts.php?deleteItemOnCart`,
                        type: "POST",
                        data: {
                            item_id: item_id
                        },
                        success: (response) => {
                            const json = JSON.parse(response);
                            if (json.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'สำเร็จ!',
                                    text: json.message,
                                    customClass: {
                                        popup: 'border-radius-sweetAlert',
                                        confirmButton: 'border-radius-sweetAlert'
                                    }
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ข้อผิดพลาด!',
                                    text: json.message,
                                    customClass: {
                                        popup: 'border-radius-sweetAlert',
                                        confirmButton: 'border-radius-sweetAlert'
                                    }
                                });
                            }
                        },
                        error: (xhr, status, error) => {
                            console.error('Error deleting item:', error);
                        }
                    });
                }
            });
        };
        <?php if ($carts->rowCount() > 0) { ?>
            updateTotalPrice();
        <?php } ?>
    </script>

</body>

</html>