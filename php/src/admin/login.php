<?php
session_start();
require_once __DIR__ . "/../lib/util.php";
$admin_id = isset($_SESSION["user_login"]) ? header("location: /") : (isset($_SESSION["admin_login"]) ? header("location: /") : null);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN ADMIN</title>
    <?php require_once __DIR__ . "/../lib/link.php"; ?>
</head>

<body style="background-image: linear-gradient(6deg, var(--bs-primary-fade), transparent);">
    <div class="d-flex justify-content-center vh-100 align-items-center">
        <div class="d-flex align-items-center p-4 h-100" style="width: 500px;">
            <div class="w-100">
                <form id="login-form" method="post">
                    <h1 class="mb-2 text-center">เข้าสู่ระบบแอดมิน</h1>
                    <h3 class="mb-3 text-center">ยินดีต้อนรับกลับมา</h3>
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
                    <a href="/" class="text-primary">กลับไปยังหน้าแรก</a>
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
                                    window.location.href = "./index.php";
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