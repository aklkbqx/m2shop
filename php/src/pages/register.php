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
    <title>สมัครสมาชิก | Register</title>
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
        <div class="d-flex align-items-center border bg-white rounded-4 shadow-sm">
            <div class="position-absolute z-2" style="top:20px;left:20px">
                <a href="/" class="d-flex align-items-center gap-2 text-primary">
                    <i class="fa-solid fa-chevron-left"></i>
                    กลับ
                </a>
            </div>
            <div class="d-flex align-items-center p-5 h-100 responsive-register">
                <div class="w-100">
                    <form id="register-form" method="post" class="position-relative">
                        <h1 class="mb-4 text-center">สมัครสมาชิก</h1>
                        <div class="d-flex gap-2 w-100">
                            <div class="form-floating mb-3 w-100">
                                <input type="text" name="firstname" id="firstname" class="form-control rounded-4" placeholder="ชื่อ" required>
                                <label for="firstname">ชื่อ</label>
                            </div>
                            <div class="form-floating mb-3 w-100">
                                <input type="text" name="lastname" id="lastname" class="form-control rounded-4" placeholder="นามสกุล" required>
                                <label for="lastname">นามสกุล</label>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" name="email" id="email" class="form-control rounded-4" placeholder="อีเมล" required>
                            <label for="email">อีเมล</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea type="text" name="address" id="address" class="form-control rounded-4" placeholder="ที่อยู่" style="min-height: 100px;" required></textarea>
                            <label for="address">ที่อยู่</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" name="tel" id="tel" class="form-control rounded-4" placeholder="เบอร์โทรศัพท์" required>
                            <label for="tel">เบอร์โทรศัพท์</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" name="password" id="password" class="form-control rounded-4" placeholder="รหัสผ่าน" required autocomplete="new-password">
                            <label for="password">รหัสผ่าน</label>
                            <div class="position-absolute cursor-pointer" style="top: 20px;right: 20px;" id="showPassword1">
                                <i class="fa-solid fa-eye fs-5 eyeicon"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end position-absolute text-danger z-1" style="right: 15px;opacity:0;bottom: 109px;font-size: 14px;" id="text-validate-password">
                            รหัสผ่านไม่ตรงกัน
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" name="c_password" id="c_password" class="form-control rounded-4" placeholder="ยืนยันรหัสผ่านอีกครั้ง" required autocomplete="new-password">
                            <label for="c_password">ยืนยันรหัสผ่านอีกครั้ง</label>
                            <div class="position-absolute cursor-pointer" style="top: 20px;right: 20px;" id="showPassword2">
                                <i class="fa-solid fa-eye fs-5 eyeicon"></i>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary btn w-100 rounded-4">สมัครสมาชิก</button>
                    </form>
                    <div class="text-center mt-2">
                        <p>ฉันมีบัญชีอยู่แล้ว</p>
                        <a href="./login.php" class="text-primary">เข้าสู่ระบบ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(() => {
            $("#showPassword1").on("click", () => {
                showPassword($("#showPassword1"), $("#password"))
            })
            $("#showPassword2").on("click", () => {
                showPassword($("#showPassword2"), $("#c_password"))
            })

            $("#register-form").on("submit", function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "../api/manage_user_admin.php?register",
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
                                window.location.href = "/";
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
            checkPasswordMatch()
        })
    </script>
</body>

</html>