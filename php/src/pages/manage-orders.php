<?php
session_start();
require_once __DIR__ . "/../lib/util.php";

if ($_SERVER["REQUEST_URI"] == "/pages/manage-orders.php" || $_SERVER["REQUEST_URI"] == "/pages/manage-orders.php?tabs=") {
    header("location: /pages/manage-orders.php?tabs=all");
    exit;
}

$user_id = isset($_SESSION["user_login"]) ? $_SESSION["user_login"] : (isset($_SESSION["admin_login"]) ? header("location: ../") : header("location: ./login.php"));
if ($user_id) {
    $row = sql("SELECT * FROM `users` WHERE `user_id` = ?", [$user_id])->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <?php require_once __DIR__ . "/../lib/link.php"; ?>
</head>

<body class="bg-primary">
    <?php require_once __DIR__ . "/../components/navbar.php"; ?>

    <div class="p-2">
        <div class="container " style="margin-top: 7rem;">
            <div class="d-flex justify-content-start">
                <a href="../" class="d-flex align-items-center text-white gap-2">
                    <i class="fa-solid fa-left-long"></i>
                    <h5>กลับ</h5>
                </a>
            </div>
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="d-flex align-items-center my-3 fw-semibold fs-1 text-white">คำสั่งซื้อของฉัน <i class="fa-solid fa-list-check ms-2"></i></h4>

                <?php
                $ordersTabsName = [
                    "all",
                    "waiting",
                    "delivering",
                    "successfully",
                    "canceled",
                ];
                ?>

                <div class="d-flex justify-content-center mb-2">
                    <ul class="m-0 p-0 list-unstyled d-flex flex-row flex-wrap gap-2 justify-content-center">
                        <?php
                        foreach ($ordersTabsName as $index => $tabname) { ?>
                            <a href="/pages/manage-orders.php?tabs=<?= $tabname; ?>" class="text-decoration-none fs-5 fw-medium btn rounded-4 <?= isset($_GET["tabs"]) && $_GET["tabs"] == $tabname ? "text-white btn-secondary" : "btn-light text-primary" ?>">
                                <li>
                                    <?php
                                    $countAllOrders = sql("SELECT COUNT(*) as countAllOrders FROM `orders` WHERE `user_id` = ?", [$row["user_id"]])->fetch()["countAllOrders"];
                                    $countStatus = sql("SELECT COUNT(*) as countStatus FROM `orders` WHERE `user_id` = ? AND `status` = ?", [$row['user_id'], $tabname])->fetch()['countStatus'];
                                    echo $tabname == "all" ? "ทั้งหมด ($countAllOrders)" : ($tabname == "waiting" ? "กำลังรออนุมัติ" : ($tabname == "delivering" ? "กำลังจัดส่ง" : ($tabname == "successfully" ? "สำเร็จแล้ว" : ($tabname == "canceled" ? "ยกเลิกแล้ว" : ""))));
                                    echo $countStatus != 0 ? " ($countStatus)" : null;
                                    ?>
                                </li>
                            </a>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <?php
            $pramsTabs = isset($_GET["tabs"]) && $_GET["tabs"] != "all" ? $_GET["tabs"] . "%" : "%";
            $items = sql("SELECT * FROM `orders` WHERE user_id = ? AND status LIKE '$pramsTabs' ORDER BY `create_at` DESC", [$row['user_id']]);
            if ($items->rowCount() > 0) {
                while ($item = $items->fetch()) { ?>
                    <div class="mb-3 rounded-4 table-responsive text-nowrap">
                        <table class="table m-0">
                            <thead class="table-secondary text-center">
                                <tr>
                                    <th scope="col" style="width: 100px;">#</th>
                                    <th scope="col" style="width: 300px;">สินค้า</th>
                                    <th scope="col" style="width: 380px;">จำนวนสินค้า</th>
                                    <th scope="col" style="width: 300px;">ราคา รวม</th>
                                    <th scope="col" style="width: 150px;">สถานะการสั่งซื้อ</th>
                                </tr>
                            </thead>

                            <tbody class="text-center">
                                <?php
                                $oders_json = json_decode($item["oders_json"], true);
                                $totalPrice = $oders_json["totalPrice"];
                                $orderItem = 1;
                                foreach ($oders_json["carts"] as $order) {
                                    $product = sql("SELECT * FROM `products` WHERE product_id = ?", [$order["product_id"]])->fetch(); ?>
                                    <tr class="align-middle">
                                        <th scope="row"><?= $orderItem; ?></th>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="<?= pathImage($product["product_image"], "product_images"); ?>" width="100px" height="100px" class="object-fit-cover rounded-3">
                                                <div class="d-flex flex-column align-items-start">
                                                    <h5><?= $product["name"] ?></h5>
                                                    <h6 class="text-muted">ขนาด: <?= $order["size"] ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <?= $order["amount"]; ?>
                                            </div>
                                        </td>
                                        <td><?= formatNumberWithComma($product["price"] * $order["amount"]); ?> บาท</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1 <?= $item["status"] == "waiting" ? "text-primary" : ($item["status"] == "canceled" ? "text-danger" : ($item["status"] == "successfully" ? "text-success" : ($item["status"] == "delivering" ? "text-warning" : null))) ?>">
                                                <?= $item["status"] == "waiting" ? "กำลังรออนุมัติ <div class='spinner-border' style='width:20px;height:20px' role='status'></div>" : ($item["status"] == "canceled" ? "ยกเลิกแล้ว" : ($item["status"] == "successfully" ? "จัดส่งสำเร็จแล้ว" : ($item["status"] == "delivering" ? "กำลังจัดส่ง... <div class='spinner-grow' style='width:20px;height:20px' role='status'></div>" : null))) ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php $orderItem++;
                                } ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <th colspan="5" class="p-4 fs-5">
                                        <div class="d-flex justify-content-between">
                                            <div class="fst-italic fw-light">#เก็บเงินปลายทาง</div>
                                            <div>
                                                <i class="fa-solid fa-money-bill text-success"></i>
                                                รวมเป็นเงินทั้งหมด:
                                                <span class="text-success"><?= formatNumberWithComma($oders_json["totalPrice"]); ?> บาท</span>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php }
            } else { ?>
                <div class="d-flex justify-content-center mt-5">
                    <h4 class="text-muted">ยังไม่มีข้อมูล..</h4>
                </div>
            <?php } ?>
        </div>
    </div>
</body>

</html>