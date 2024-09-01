<?php
function sql(string $sql, array $params = [])
{
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

if (isset($_GET["logout"])) {
    $session_check = isset($_SESSION["user_login"]) ? "user_login" : (isset($_SESSION["admin_login"]) ? "admin_login" : null);
    if ($session_check) {
        unset($_SESSION[$session_check]);
        header("location: /");
    }
}

function msg(string $msg, string $type_msg, string $location)
{
    $_SESSION[$type_msg] = $msg;
    header("location: " . $location);
    exit;
}

function showMessageAlert(string $type_msg)
{
    if (isset($_SESSION[$type_msg])) { ?>
        <div class='position-fixed opacity-0' style="top:0rem;right:1rem;z-index:99999" id="showMsg">
            <div class='alert alert-<?= $type_msg; ?> border border-<?= $type_msg; ?> border-3 position-relative p-4 shadow-sm rounded-4' style='width:350px;'>
                <div class='position-absolute d-flex' style='top:5px;right:5px;gap:5px'>
                    <div class='d-flex text-muted'>
                        <div id="count">4</div>s
                    </div>
                    <button type="button" onclick='close_showMsg()' class='btn-close shadow-none'></button>
                </div>
                <div class='d-flex align-items-center' style='gap:10px'>
                    <?= $type_msg == "success" ? '<svg xmlns="http://www.w3.org/2000/svg" width="35px" height="35px" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM16.0303 8.96967C16.3232 9.26256 16.3232 9.73744 16.0303 10.0303L11.0303 15.0303C10.7374 15.3232 10.2626 15.3232 9.96967 15.0303L7.96967 13.0303C7.67678 12.7374 7.67678 12.2626 7.96967 11.9697C8.26256 11.6768 8.73744 11.6768 9.03033 11.9697L10.5 13.4393L12.7348 11.2045L14.9697 8.96967C15.2626 8.67678 15.7374 8.67678 16.0303 8.96967Z" fill="#198754"/></svg>'
                        : ($type_msg == "danger" ? '<svg xmlns="http://www.w3.org/2000/svg" width="35px" height="35px" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12ZM12 6.25C12.4142 6.25 12.75 6.58579 12.75 7V13C12.75 13.4142 12.4142 13.75 12 13.75C11.5858 13.75 11.25 13.4142 11.25 13V7C11.25 6.58579 11.5858 6.25 12 6.25ZM12 17C12.5523 17 13 16.5523 13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17Z" fill="#dc3545"/></svg>'
                            : ($type_msg == "warning" ? '<svg xmlns="http://www.w3.org/2000/svg" width="35px" height="35px" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.31171 10.7615C8.23007 5.58716 9.68925 3 12 3C14.3107 3 15.7699 5.58716 18.6883 10.7615L19.0519 11.4063C21.4771 15.7061 22.6897 17.856 21.5937 19.428C20.4978 21 17.7864 21 12.3637 21H11.6363C6.21356 21 3.50217 21 2.40626 19.428C1.31034 17.856 2.52291 15.7061 4.94805 11.4063L5.31171 10.7615ZM12 7.25C12.4142 7.25 12.75 7.58579 12.75 8V13C12.75 13.4142 12.4142 13.75 12 13.75C11.5858 13.75 11.25 13.4142 11.25 13V8C11.25 7.58579 11.5858 7.25 12 7.25ZM12 17C12.5523 17 13 16.5523 13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17Z" fill="#ffc107"/></svg>' : "")) ?>
                    <?= $_SESSION[$type_msg];
                    unset($_SESSION[$type_msg]); ?>
                </div>
            </div>
        </div>
        <script id="scriptMsg">
            const showMsg = document.getElementById('showMsg');
            const count = document.getElementById('count');
            const scriptMsg = document.getElementById('scriptMsg');

            let timeleft = 4;
            let countdown = setInterval(() => {
                timeleft -= 1;
                count.textContent = timeleft;
                if (timeleft <= 0) {
                    clearInterval(countdown);
                    showMsg.remove();
                    scriptMsg.remove();
                }
            }, 1000);

            setTimeout(() => {
                showMsg.classList.remove('opacity-0');
                showMsg.style.top = '2rem';
                setTimeout(() => {
                    close_showMsg();
                }, 3000);
            }, 1);

            function close_showMsg() {
                showMsg.classList.add('opacity-0');
                showMsg.style.top = '5rem';
                setTimeout(() => {
                    showMsg.remove();
                    scriptMsg.remove();
                }, 500);
            }
        </script>
    <?php }
}
$messageAlerts = [
    "success",
    "danger",
    "warning"
];
foreach ($messageAlerts as $messageType) {
    if (isset($_SESSION[$messageType])) {
        showMessageAlert($messageType);
    }
}
function linkPage(string $page, string $location = "pages")
{
    $request_uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    return $request_uri == "/" ? "./$location/$page" : "$page";
}
function pathImage($image, string $typeImage = "user_images", string $location = "assets/images")
{
    $request_uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    return $request_uri == "/" ? "./$location/$typeImage/$image" : "../$location/$typeImage/$image";
}
function formatNumberWithComma(string $number)
{
    return number_format($number, 0, '.', ',');
}
function searchMenu()
{ ?>
    <div class="gap-2 d-none d-md-flex">
        <input type="search" class="form-control fs-6 rounded-4" placeholder="ค้นหา" id="searchInputNavbar">
        <button type="button" class="btn btn-outline-primary fs-5 rounded-4" onclick='searchProduct($("#searchInputNavbar").val())'>
            <i class="fa-solid fa-magnifying-glass fs-5"></i>
        </button>
    </div>
<?php }

function sessionAccess(array $allowed_roles, array $blocked_roles)
{
    $user_id = isset($_SESSION["user_login"]) ? $_SESSION["user_login"] : null;
    if ($user_id === null) {
        if (in_array('guest', $allowed_roles)) {
            return null;
        } elseif (in_array('guest', $blocked_roles)) {
            header("Location: " . linkPage("login.php"));
            exit;
        }
    } else {
        $user_type = sql("SELECT `role` FROM `users` WHERE `user_id` = ?", [$user_id])->fetchColumn();
        if (in_array($user_type, $allowed_roles)) {
            return $user_id;
        }

        if (in_array($user_type, $blocked_roles)) {
            if ($user_type == "admin") {
                header("Location: /admin");
            } else {
                header("Location: /");
            }
            exit;
        }
    }

    return null;
}
?>