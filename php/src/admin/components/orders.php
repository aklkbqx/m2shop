<h1 class="d-flex align-items-center mb-4" style="gap:10px"><i class="fa-solid fa-clipboard-list"></i> จัดการคำสั่งซื้อที่เพิ่มเข้ามาจากลูกค้า</h1>
<?php
$ordersTabsName = [
    "all",
    "waiting",
    "delivering",
    "successfully",
    "canceled",
];
?>

<div class="d-flex justify-content-center">
    <ul class="m-0 p-0 list-unstyled d-flex flex-sm-row gap-4 flex-column">
        <?php
        foreach ($ordersTabsName as $index => $tabname) { ?>
            <a href="/admin/?page=orders&tabs=<?= $tabname; ?>" class="text-decoration-none text-primary fs-5 fw-medium btn rounded-4 <?= isset($_GET["tabs"]) && $_GET["tabs"] == $tabname ? "active btn-primary" : "btn-light" ?>">
                <li>
                    <?php
                    $countAllOrders = sql("SELECT COUNT(*) as countAllOrders FROM `orders`", [])->fetch()["countAllOrders"];
                    $countStatus = sql("SELECT COUNT(*) as countStatus FROM `orders` WHERE `status` = ?", [$tabname])->fetch()['countStatus'];
                    echo $tabname == "all" ? "ทั้งหมด ($countAllOrders)" : ($tabname == "waiting" ? "กำลังรออนุมัติ" : ($tabname == "delivering" ? "กำลังจัดส่ง" : ($tabname == "successfully" ? "สำเร็จแล้ว" : ($tabname == "canceled" ? "ยกเลิกแล้ว" : ""))));
                    echo $countStatus != 0 ? " ($countStatus)" : null;
                    ?>
                </li>
            </a>
        <?php } ?>
    </ul>
</div>
<div class="mt-4 p-2 overflow-y-auto overflow-x-hidden" style="height: calc(100% - 15%);">
    <?php
    $pramsTabs = isset($_GET["tabs"]) && $_GET["tabs"] != "all" ? $_GET["tabs"] . "%" : "%";
    $items = sql("SELECT * FROM `orders` WHERE status LIKE '$pramsTabs' ORDER BY `create_at` DESC");
    if ($items->rowCount() > 0) {
        while ($item = $items->fetch()) {
            $oders_json = json_decode($item["oders_json"], true);
            $user = sql("SELECT * FROM `users` WHERE `user_id` = ?", [$oders_json["user_id"]])->fetch(); ?>
            <div class="bg-white w-100 p-4 rounded-4 shadow-sm border mb-5 position-relative">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center" style="gap:10px">
                        <img src="<?= pathImage($user["profile_image"], "user_images") ?>" width="80px" height="80px" class="rounded-circle border object-fit-cover">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex flex-row fs-5 fw-bold" style="gap:5px;">
                                    <div><?= $user["firstname"] ?></div>
                                    <div><?= $user["lastname"] ?></div>
                                </div>
                            </div>
                            ทำการสั่งซื้อมาเมื่อเวลา <span><?= $item["create_at"] ?></span>
                        </div>
                    </div>
                    <div class="fs-5 d-flex align-items-center gap-2 text-white">
                        <div class="d-flex align-items-center p-2 rounded-5 gap-2 px-3 <?= $item["status"] == "waiting" ? "bg-primary" : ($item["status"] == "canceled" ? "bg-danger" : ($item["status"] == "successfully" ? "bg-success" : ($item["status"] == "delivering" ? "bg-warning" : null))) ?>">
                            <?= $item["status"] == "waiting" ? 'กำลังรอคุณอนุมัติเริ่มจัดส่ง <div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
                                : ($item["status"] == "canceled" ? 'ยกเลิกแล้ว <i class="fa-solid fa-circle-xmark fs-3"></i>'
                                    : ($item["status"] == "successfully" ? 'จัดส่งสำเร็จแล้ว <i class="fa-solid fa-circle-check fs-3"></i>'
                                        : ($item["status"] == "delivering" ? 'กำลังจัดส่ง... <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#fff" height="40px" width="40px" version="1.1" id="Capa_1" viewBox="0 0 422.518 422.518" xml:space="preserve"><path d="M422.512,215.424c0-0.079-0.004-0.158-0.005-0.237c-0.116-5.295-4.368-9.514-9.727-9.514h-2.554l-39.443-76.258  c-1.664-3.22-4.983-5.225-8.647-5.226l-67.34-0.014l2.569-20.364c0.733-8.138-1.783-15.822-7.086-21.638  c-5.293-5.804-12.683-9.001-20.81-9.001h-209c-5.255,0-9.719,4.066-10.22,9.308l-2.095,16.778h119.078  c7.732,0,13.836,6.268,13.634,14c-0.203,7.732-6.635,14-14.367,14H126.78c0.007,0.02,0.014,0.04,0.021,0.059H10.163  c-5.468,0-10.017,4.432-10.16,9.9c-0.143,5.468,4.173,9.9,9.641,9.9H164.06c7.168,1.104,12.523,7.303,12.326,14.808  c-0.216,8.242-7.039,14.925-15.267,14.994H54.661c-5.523,0-10.117,4.477-10.262,10c-0.145,5.523,4.215,10,9.738,10h105.204  c7.273,1.013,12.735,7.262,12.537,14.84c-0.217,8.284-7.109,15-15.393,15H35.792v0.011H25.651c-5.523,0-10.117,4.477-10.262,10  c-0.145,5.523,4.214,10,9.738,10h8.752l-3.423,35.818c-0.734,8.137,1.782,15.821,7.086,21.637c5.292,5.805,12.683,9.001,20.81,9.001  h7.55C69.5,333.8,87.3,349.345,109.073,349.345c21.773,0,40.387-15.545,45.06-36.118h94.219c7.618,0,14.83-2.913,20.486-7.682  c5.172,4.964,12.028,7.682,19.514,7.682h1.55c3.597,20.573,21.397,36.118,43.171,36.118c21.773,0,40.387-15.545,45.06-36.118h6.219  c16.201,0,30.569-13.171,32.029-29.36l6.094-67.506c0.008-0.091,0.004-0.181,0.01-0.273c0.01-0.139,0.029-0.275,0.033-0.415  C422.52,215.589,422.512,215.508,422.512,215.424z M109.597,329.345c-13.785,0-24.707-11.214-24.346-24.999  c0.361-13.786,11.87-25.001,25.655-25.001c13.785,0,24.706,11.215,24.345,25.001C134.89,318.131,123.382,329.345,109.597,329.345z   M333.597,329.345c-13.785,0-24.706-11.214-24.346-24.999c0.361-13.786,11.87-25.001,25.655-25.001  c13.785,0,24.707,11.215,24.345,25.001C358.89,318.131,347.382,329.345,333.597,329.345z M396.457,282.588  c-0.52,5.767-5.823,10.639-11.58,10.639h-6.727c-4.454-19.453-21.744-33.882-42.721-33.882c-20.977,0-39.022,14.429-44.494,33.882  h-2.059c-2.542,0-4.81-0.953-6.389-2.685c-1.589-1.742-2.337-4.113-2.106-6.676l12.609-139.691l28.959,0.006l-4.59,50.852  c-0.735,8.137,1.78,15.821,7.083,21.637c5.292,5.806,12.685,9.004,20.813,9.004h56.338L396.457,282.588z" /></svg>' : null))) ?>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="d-flex flex-row align-items-center gap-2 flex-wrap">
                        <h5>รายละเอียดที่อยู่ในการจัดส่ง: </h5><span class="fs-5"><?= $user["address"] ?></span>
                    </div>
                    <div class="d-flex flex-row align-items-center gap-2">
                        <h5>เบอร์โทรศัพท์: </h5><span class="fs-5"><?= $user["tel"] ?></span>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table my-3">
                        <thead class="text-center">
                            <tr>
                                <th scope="col" style="width: 50px;">#</th>
                                <th scope="col" style="width: 300px;">สินค้า</th>
                                <th scope="col" style="width: 113px;">จำนวนสินค้า</th>
                                <th scope="col" style="width: 120px;">ราคา รวม</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php
                            foreach ($oders_json["carts"] as $index => $order) {
                                $product = sql("SELECT * FROM `products` WHERE `product_id` = ?", [$order["product_id"]])->fetch();  ?>
                                <tr class="align-middle">
                                    <th scope="row"><?= $index + 1; ?></th>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="<?= pathImage($product["product_image"], "product_images"); ?>" width="100px" height="100px" class="object-fit-cover rounded-4">
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
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <th colspan="5" class="text-end p-4 fs-5">
                                    <i class="fa-solid fa-money-bill text-success"></i>
                                    รวมเป็นเงินทั้งหมด:
                                    <span class="text-success"> <?= formatNumberWithComma($oders_json["totalPrice"]); ?> บาท</span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <?php if ($item["status"] == "waiting") { ?>
                        <a href="../api/manage_orders.php?cancelOrder&order_id=<?= $item["order_id"] ?>" class="btn btn-danger d-flex align-items-center w-100 justify-content-center rounded-4" style="gap:10px"><i class="fa-solid fa-circle-xmark"></i> ยกเลิก</a>
                        <a href="../api/manage_orders.php?deliveryOrder&order_id=<?= $item["order_id"] ?>" class="btn btn-warning d-flex align-items-center w-100 justify-content-center rounded-4" style="gap:10px">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="25px" width="25px" version="1.1" id="Capa_1" viewBox="0 0 422.518 422.518" xml:space="preserve">
                                <path d="M422.512,215.424c0-0.079-0.004-0.158-0.005-0.237c-0.116-5.295-4.368-9.514-9.727-9.514h-2.554l-39.443-76.258  c-1.664-3.22-4.983-5.225-8.647-5.226l-67.34-0.014l2.569-20.364c0.733-8.138-1.783-15.822-7.086-21.638  c-5.293-5.804-12.683-9.001-20.81-9.001h-209c-5.255,0-9.719,4.066-10.22,9.308l-2.095,16.778h119.078  c7.732,0,13.836,6.268,13.634,14c-0.203,7.732-6.635,14-14.367,14H126.78c0.007,0.02,0.014,0.04,0.021,0.059H10.163  c-5.468,0-10.017,4.432-10.16,9.9c-0.143,5.468,4.173,9.9,9.641,9.9H164.06c7.168,1.104,12.523,7.303,12.326,14.808  c-0.216,8.242-7.039,14.925-15.267,14.994H54.661c-5.523,0-10.117,4.477-10.262,10c-0.145,5.523,4.215,10,9.738,10h105.204  c7.273,1.013,12.735,7.262,12.537,14.84c-0.217,8.284-7.109,15-15.393,15H35.792v0.011H25.651c-5.523,0-10.117,4.477-10.262,10  c-0.145,5.523,4.214,10,9.738,10h8.752l-3.423,35.818c-0.734,8.137,1.782,15.821,7.086,21.637c5.292,5.805,12.683,9.001,20.81,9.001  h7.55C69.5,333.8,87.3,349.345,109.073,349.345c21.773,0,40.387-15.545,45.06-36.118h94.219c7.618,0,14.83-2.913,20.486-7.682  c5.172,4.964,12.028,7.682,19.514,7.682h1.55c3.597,20.573,21.397,36.118,43.171,36.118c21.773,0,40.387-15.545,45.06-36.118h6.219  c16.201,0,30.569-13.171,32.029-29.36l6.094-67.506c0.008-0.091,0.004-0.181,0.01-0.273c0.01-0.139,0.029-0.275,0.033-0.415  C422.52,215.589,422.512,215.508,422.512,215.424z M109.597,329.345c-13.785,0-24.707-11.214-24.346-24.999  c0.361-13.786,11.87-25.001,25.655-25.001c13.785,0,24.706,11.215,24.345,25.001C134.89,318.131,123.382,329.345,109.597,329.345z   M333.597,329.345c-13.785,0-24.706-11.214-24.346-24.999c0.361-13.786,11.87-25.001,25.655-25.001  c13.785,0,24.707,11.215,24.345,25.001C358.89,318.131,347.382,329.345,333.597,329.345z M396.457,282.588  c-0.52,5.767-5.823,10.639-11.58,10.639h-6.727c-4.454-19.453-21.744-33.882-42.721-33.882c-20.977,0-39.022,14.429-44.494,33.882  h-2.059c-2.542,0-4.81-0.953-6.389-2.685c-1.589-1.742-2.337-4.113-2.106-6.676l12.609-139.691l28.959,0.006l-4.59,50.852  c-0.735,8.137,1.78,15.821,7.083,21.637c5.292,5.806,12.685,9.004,20.813,9.004h56.338L396.457,282.588z" />
                            </svg> เริ่มจัดส่ง</a>
                    <?php } elseif ($item["status"] == "delivering") { ?>
                        <a href="../api/manage_orders.php?successfullyOrder&order_id=<?= $item["order_id"] ?>" class="btn btn-success d-flex align-items-center w-100 justify-content-center rounded-4" style="gap:10px"><i class="fa-solid fa-circle-check"></i> จัดส่งสำเร็จ</a>
                    <?php } ?>
                </div>
            </div>

        <?php }
    } else { ?>
        <div class="d-flex align-items-center justify-content-center h-100 w-100 p-5 pb-0" style="gap: 10px;">
            <h3 class="text-muted">ยังไม่มีรายการ...</h3>
            <div class="d-flex align-items-center" style="gap:10px">
                <div class="spinner-grow spinner-grow-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow" role="status" style="width: 1.5rem !important;height: 1.5rem !important;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow spinner-grow-lg" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    <?php } ?>
</div>