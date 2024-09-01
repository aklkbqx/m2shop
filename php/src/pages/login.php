<?php
session_start();
require_once __DIR__ . "/../lib/util.php";
$user_id = isset($_SESSION["user_login"]) ? $_SESSION["user_login"] : (isset($_SESSION["admin_login"]) ? header("location: ../admin/") : null);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ | Login</title>
    <?php require_once __DIR__ . "/../lib/link.php"; ?>
</head>

<body class="bg-light">

    <div style="background-image: url('https://i.pinimg.com/564x/72/e4/02/72e40213df2c4b17814955c039ade068.jpg');
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    width: 100%;
    height: 100vh;
    filter: blur(10px);"></div>

    <div class="position-absolute" style="transform: translate(-50%, -50%);top: 50%;left: 50%;">
        <div class="d-flex align-items-center border bg-white rounded-4 shadow-sm py-5 responsive-login">
            <div class="position-absolute" style="top:20px;left:20px">
                <a href="/" class="d-flex align-items-center gap-2 text-primary">
                    <i class="fa-solid fa-chevron-left"></i>
                    กลับ
                </a>
            </div>
            <img src="<?= pathImage("logo.png", "web")  ?>" alt="banner login" width="400px" height="auto" class="rounded-4 d-none d-md-block">
            <div class="d-flex align-items-center p-4 h-100" style="width: 400px;">
                <div class="w-100">
                    <form id="login-form" method="post">
                        <h1 class="mb-2 text-center">เข้าสู่ระบบ</h1>
                        <div class="form-floating mb-3">
                            <input type="text" name="email" id="email" class="form-control rounded-4" placeholder="อีเมล" required autocomplete="email">
                            <label for="email">อีเมล</label>
                        </div>
                        <div class="form-floating mb-3 position-relative">
                            <input type="password" name="password" id="password" class="form-control rounded-4" placeholder="รหัสผ่าน" required autocomplete="new-password">
                            <label for="password">รหัสผ่าน</label>
                            <div class="position-absolute cursor-pointer" style="top: 20px;right: 20px;" id="showPassword">
                                <i class="fa-solid fa-eye fs-5 eyeicon"></i>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary btn w-100 rounded-4">เข้าสู่ระบบ</button>
                    </form>
                    <div class="text-center mt-2">
                        <p>หากยังไม่มีบัญชี</p>
                        <a href="./register.php" class="text-primary">สมัครสมาชิก</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(() => {
            $("#showPassword").on("click", () => {
                showPassword($("#showPassword"), $("#password"))
            })

            $("#login-form").on("submit", function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "../api/manage_user_admin.php?login",
                    data: formData,
                    success: (response) => {
                        const data = JSON.parse(response);
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ!',
                                text: data.message,
                                confirmButtonText: 'ตกลง',
                                customClass: {
                                    popup: 'border-radius-sweetAlert',
                                    confirmButton: 'border-radius-sweetAlert',
                                    cancelButton: 'border-radius-sweetAlert',
                                    denyButton: 'border-radius-sweetAlert'
                                }
                            }).then(() => {
                                if (data.role === "admin") {
                                    window.location.href = "../admin/index.php";
                                } else if (data.role === "user") {
                                    window.location.href = "/";
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                text: data.message,
                                confirmButtonText: 'ลองอีกครั้ง',
                                customClass: {
                                    popup: 'border-radius-sweetAlert',
                                    confirmButton: 'border-radius-sweetAlert',
                                    cancelButton: 'border-radius-sweetAlert',
                                    denyButton: 'border-radius-sweetAlert'
                                }
                            });
                        }
                    },
                    error: () => {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้',
                            confirmButtonText: 'ลองอีกครั้ง',
                            customClass: {
                                popup: 'border-radius-sweetAlert',
                                confirmButton: 'border-radius-sweetAlert',
                                cancelButton: 'border-radius-sweetAlert',
                                denyButton: 'border-radius-sweetAlert'
                            }
                        });
                    }
                })
            })
        })
    </script>
</body>

</html>