<form method="post" enctype="multipart/form-data" id="formData">
    <div class="d-flex align-items-center justify-content-between mt-2 flex-column flex-sm-row gap-2">
        <h1>การตั้งค่าบัญชีของฉัน</h1>
        <div>
            <button type="reset" class="btn btn-secondary fs-5 rounded-4">ยกเลิก</button>
            <button type="submit" name="saveChange-account" class="btn btn-primary fs-5 rounded-4"><i class="fa-solid fa-floppy-disk"></i> บันทึก</button>
        </div>
    </div>
    <div class="d-flex flex-column align-items-center py-3 rounded-4">
        <img src="<?= pathImage($row["profile_image"]); ?>" id="profile_image_preview" width="200px" height="200px" class="rounded-circle border cursor-pointer" style="object-fit:cover" onclick="document.getElementById('profile').click()">
        <div class="d-flex align-items-center mt-3" style="gap:10px">
            <label for="profile" class="btn btn-primary rounded-4"><i class="fa-solid fa-image"></i> เปลี่ยนรูปภาพ</label>
            <button id="trash-can" type="button" class="btn btn-danger d-none" onclick="document.getElementById('profile_image_preview').src = '<?= pathImage($row['profile_image']); ?>'; document.getElementById('profile').value=''; this.classList.add('d-none')">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>
    </div>

    <div class="form-floating mt-4">
        <input type="text" class="form-control rounded-4" name="firstname" id="firstname" placeholder="Firstname" required value="<?= $row["firstname"] ?>">
        <label for="firstname">ชื่อ</label>
    </div>
    <div class="form-floating mt-4">
        <input type="text" class="form-control rounded-4" name="lastname" id="lastname" placeholder="Lastname" required value="<?= $row["lastname"] ?>">
        <label for="lastname">นามสกุล</label>
    </div>
    <?php if (isset($_SESSION["user_login"])) { ?>
        <div class="form-floating mt-4">
            <textarea class="form-control rounded-4" name="address" id="address" placeholder="address" style="min-height: 100px;" required><?= $row["address"] ?></textarea>
            <label for="address">ที่อยู่</label>
        </div>
        <div class="form-floating mt-4">
            <input type="number" class="form-control rounded-4" name="tel" id="tel" placeholder="0xxxxxxxx" required value="<?= $row["tel"] ?>">
            <label for="tel">เบอร์โทรศัพท์</label>
        </div>
    <?php } ?>
    <div class="form-floating mt-4">
        <input type="email" class="form-control rounded-4" name="email" id="email" placeholder="name@example.com" required value="<?= $row["email"] ?>" <?= isset($_SESSION["user_login"]) ? 'readonly style="background-color: #dedede;"' : "" ?>>
        <label for="email">อีเมล</label>
    </div>
    <?php if (isset($_SESSION["user_login"])) { ?>
        <div class="form-floating mt-4">
            <input type="password" class="form-control rounded-4" name="old_password" id="old_password" placeholder="Old Password">
            <label for="old_password">รหัสผ่านเดิม</label>
        </div>
    <?php } ?>
    <div class="form-floating mt-4">
        <input type="password" class="form-control rounded-4" name="password" id="password" placeholder="Password">
        <label for="password">รหัสผ่านใหม่</label>
        <div class="position-absolute cursor-pointer" style="top: 20px;right: 20px;" id="showPassword1">
            <i class="fa-solid fa-eye fs-5 eyeicon"></i>
        </div>
    </div>
    <div class="form-floating mt-4 pb-2">
        <input type="password" class="form-control rounded-4" name="c_password" id="c_password" placeholder="Confirm Password">
        <label for="c_password">ยืนยันรหัสผ่านใหม่</label>
        <div class="position-absolute cursor-pointer" style="top: 20px;right: 20px;" id="showPassword2">
            <i class="fa-solid fa-eye fs-5 eyeicon"></i>
        </div>
    </div>
    <input name="profile" id="profile" type="file" onchange="document.getElementById('profile_image_preview').src = window.URL.createObjectURL(this.files[0]);document.getElementById('trash-can').classList.remove('d-none')" hidden>

</form>
<script>
    $(document).ready(() => {
        $("#showPassword1").on("click", () => {
            showPassword($("#showPassword1"), $("#password"))
        })
        $("#showPassword2").on("click", () => {
            showPassword($("#showPassword2"), $("#c_password"))
        })
        $("#formData").on("submit", function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "../api/manage_user_admin.php?edit-profile&user_id=<?= $row["user_id"] ?>",
                data: formData,
                processData: false,
                contentType: false,
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
                            window.location.reload(); // รีเฟรชหน้าเพื่อแสดงข้อมูลล่าสุด
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
            });
        });
        checkPasswordMatch()
    })
</script>