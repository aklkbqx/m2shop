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
    <title>จัดการโปรไฟล์ | Account Setting</title>
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
            <?php require_once "../components/manage_account.php" ?>
        </div>
    </div>
    </div>

</body>

</html>