<?php
if (($_SERVER["REQUEST_URI"] == "/admin/" || $_SERVER["REQUEST_URI"] == "/admin/index.php") || $_SERVER["REQUEST_URI"] == "/admin/?page=" || $_SERVER["REQUEST_URI"] == "/admin/index.php?page=") {
    header("location: /admin/?page=home");
    exit;
} else if ($_SERVER["REQUEST_URI"] == "/admin/?page=orders" || $_SERVER["REQUEST_URI"] == "/admin/index.php?page=orders") {
    header("location: /admin/?page=orders&tabs=all");
    exit;
}

session_start();
require_once __DIR__ . "/../lib/util.php";
$admin_id = isset($_SESSION["user_login"]) ? (header("location: /") && exit) : (isset($_SESSION["admin_login"]) ? $_SESSION["admin_login"] : (header("location: /") && exit));
if ($admin_id) {
    $row = sql("SELECT * FROM `users` WHERE `user_id` = ?", [$admin_id])->fetch(PDO::FETCH_ASSOC);
}
$linkPageAdmin = [
    [
        "thText" => "หน้าแรก",
        "engText" => "home",
        "icon" => '<i class="fa-solid fa-house"></i>'
    ],
    [
        "thText" => "จัดการสมาชิก",
        "engText" => "user",
        "icon" => '<i class="fa-solid fa-user"></i>'
    ],
    [
        "thText" => "รายการสินค้า",
        "engText" => "products",
        "icon" => '<i class="fa-solid fa-cart-shopping"></i>'
    ],
    [
        "thText" => "คำสั่งซื้อที่เพิ่มเข้ามา",
        "engText" => "orders",
        "icon" => '<i class="fa-solid fa-clipboard-list"></i>'
    ],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php require_once __DIR__ . "/../lib/link.php"; ?>
</head>

<body>
    <div class="d-flex flex-column flex-sm-row">
        <div class="bg-primary text-white d-flex flex-column justify-content-between p-4 pb-0 responsive-admin1" style="white-space: nowrap;">
            <div>
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <img src="<?= pathImage("cartoon-3.png", "web") ?>" width="50px" height="50px">
                    <div class="text-center fs-1 fw-bold">จัดการแอดมิน</div>
                </div>
                <ul class="p-0 mb-0" style="list-style:none;">
                    <?php foreach ($linkPageAdmin as $index => $link) { ?>
                        <li>
                            <a href="?page=<?= $link['engText']; ?>" class="btn btn-outline-light w-100 fs-5 text-start p-3 d-flex align-items-center mt-2 rounded-4 <?= isset($_GET["page"]) && $_GET["page"] == $link['engText'] ? "bg-white text-black" : ""; ?>" style="gap:10px;"><?= $link["icon"] . " " . $link["thText"]; ?>
                                <?php
                                $ordersBadge = sql("SELECT COUNT(*) as `count` FROM `orders` WHERE `status` = ?", ["waiting"])->fetch()["count"];
                                if ($ordersBadge) {
                                    echo $link['engText'] == "orders" ? "<span class='badge text-bg-danger rounded-circle'>$ordersBadge</span>" : null;
                                }
                                ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="responsive-admin2">
            <div class="bg-primary p-2">
                <div class="d-flex justify-content-sm-end justify-content-center">
                    <div class="dropdown">
                        <div class="d-flex justify-content-start align-items-center btn btn-outline-light border-0 rounded-5" data-bs-toggle="dropdown">
                            <div class="dropdown-toggle d-flex align-items-center" style="gap:10px">
                                <img src="<?= pathImage($row["profile_image"]); ?>" width="60px" height="60px" class="rounded-circle border object-fit-cover">
                                <h5><?= $row["firstname"] ?></h5>
                                <h5><?= $row["lastname"] ?></h5>
                            </div>
                        </div>
                        <ul class="dropdown-menu px-2 rounded-4">
                            <li><a href="?page=manage-account" class="p-3 d-flex align-items-center text-decoration-none btn btn-light rounded-4" style="gap: 10px;white-space: nowrap;"><i class="fa-solid fa-gear fs-5"></i> การจัดการบัญชีของฉัน</a></li>
                            <div class="dropdown-divider"></div>
                            <li><a href="?logout" class="p-3 d-flex align-items-center btn btn-outline-danger border-0 rounded-4" style="gap: 10px;white-space: nowrap;"><i class="fa-solid fa-right-from-bracket fs-5"></i>ออกจากระบบ!</a></li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="p-4" style="height: calc(100vh - 88px);">
                <?php foreach ($linkPageAdmin as $index => $link) {
                    if (isset($_GET["page"]) && $_GET["page"] == $link['engText']) {
                        require_once __DIR__ . "/components/" . $link['engText'] . ".php";
                    } elseif (isset($_GET["page"]) && $_GET["page"] == "manage-account") {
                        require_once "../components/manage_account.php";
                    }
                } ?>
            </div>
        </div>
    </div>
</body>

</html>