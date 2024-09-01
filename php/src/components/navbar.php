<nav class="w-100 shadow bg-white position-fixed top-0" style="z-index: 999;">
    <div class="container d-flex align-items-center">

        <div class="flex-grow-1">
            <div class="d-flex">
                <a href="/" class="text-decoration-none">
                    <img src="<?= pathImage("logo.png", "web") ?>" width="100px" height="100px" style="object-fit: cover;">
                </a>
            </div>
        </div>

        <ul class="nav-group-link flex-grow-1">
            <a href="/" class="navLink fs-5 d-none d-lg-flex">
                <li>หน้าแรก</li>
            </a>
            <div href="javascript:void(0);" class="navLink fs-5 position-relative category-dropdown d-none d-lg-flex" id="navLinkHover">
                <li>หมวดหมู่ <i class="fa-solid fa-chevron-down"></i></li>
                <div class="category-card d-none">
                    <?php $categorys = sql("SELECT * FROM `category_products`");
                    if ($categorys->rowCount() > 0) { ?>
                        <ul>
                            <?php while ($category = $categorys->fetch(PDO::FETCH_ASSOC)) { ?>
                                <a href="?category=<?= $category["cp_id"] ?>" class="category-navlink">
                                    <li><i class="fa-solid fa-circle"></i> <?= $category["name"] ?></li>
                                </a>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <div class="text-center text-primary">
                            ขณะนี้ยังไม่มีหมวดหมู่...
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php if (isset($_SESSION["user_login"])) { ?>
                <a href="<?= linkPage("manage-orders.php"); ?>" class="navLink fs-5">
                    <li>คำสั่งซื้อของฉัน</li>
                </a>
            <?php } else {
                searchMenu();
            } ?>
        </ul>

        <div class="d-flex flex-grow-1 gap-2 justify-content-end align-items-center">
            <?php if (isset($_SESSION["user_login"])) {
                searchMenu();
                $countCarts = sql("SELECT COUNT(*) AS `count` FROM `carts` WHERE `user_id` = ?", [$row["user_id"]])->fetch(PDO::FETCH_ASSOC)["count"];
            ?>
                <div class="position-relative">
                    <a href="<?= linkPage("cart.php"); ?>">
                        <button type="button" class="btn btn-outline-primary fs-5 rounded-4 p-2">
                            <i class="fa-solid fa-cart-shopping fs-5"></i>
                        </button>
                    </a>
                    <div class="position-absolute bg-danger d-flex align-items-center justify-content-center rounded-circle text-white" style="width: 20px; height:20px;top:-10px;right: -5px;">
                        <?= $countCarts; ?>
                    </div>
                </div>
                <div class="dropdown">
                    <button type="button" class="btn btn-outline-primary fs-5 rounded-4 p-2" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-user-large fs-5"></i>
                    </button>

                    <ul class="dropdown-menu p-2 rounded-4">
                        <a href="<?= linkPage("account-setting.php") ?>" class="dropdown-item rounded-4">
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?= pathImage($row["profile_image"]) ?>" class="rounded-circle border" style="width: 50px; height:50px;object-fit:cover">
                                <li>
                                    <div class="fs-5"><?= $row["firstname"] ?> <?= $row["lastname"] ?></div>
                                    <div class="text-muted">การตั้งค่าบัญชี</div>
                                </li>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <div class="cursor-pointer" id="logout-btn">
                            <li class="d-flex align-items-center gap-2 fs-5 justify-content-center btn btn-outline-danger border-0 rounded-4 px-3 py-2">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                ออกจากระบบ
                            </li>
                        </div>
                    </ul>
                </div>

                <script>
                    $("#logout-btn").on("click", () => {
                        Swal.fire({
                            icon: "warning",
                            title: "คุณต้องการออกจากระบบหรือไม่?",
                            showDenyButton: true,
                            showCancelButton: true,
                            showConfirmButton: false,
                            denyButtonText: `ออกจากระบบ`,
                            cancelButtonText: `ปิด`,
                            reverseButtons: true,
                            customClass: {
                                popup: 'border-radius-sweetAlert',
                                confirmButton: 'border-radius-sweetAlert',
                                cancelButton: 'border-radius-sweetAlert',
                                denyButton: 'border-radius-sweetAlert'
                            }
                        }).then((result) => {
                            if (result.isDenied) {
                                Swal.fire({
                                    icon: "success",
                                    title: "ออกจากระบบแล้ว",
                                    customClass: {
                                        popup: 'border-radius-sweetAlert',
                                        confirmButton: 'border-radius-sweetAlert',
                                        cancelButton: 'border-radius-sweetAlert',
                                        denyButton: 'border-radius-sweetAlert'
                                    }
                                }).then(() => {
                                    window.location.href = "?logout";
                                });
                            }
                        });
                    })
                </script>
            <?php } else { ?>
                <a href="<?= linkPage("register.php"); ?>" class="btn btn-outline-primary fs-5 rounded-4">
                    สมัครสมาชิก
                </a>
                <a href="<?= linkPage("login.php"); ?>" class="btn btn-primary fs-5 rounded-4">
                    เข้าสู่ระบบ
                </a>
            <?php } ?>
        </div>

    </div>
</nav>

<script>
    const navLinkHover = $("#navLinkHover");
    const categoryCard = $('.category-card');
    navLinkHover.on("mouseover", () => {
        categoryCard.removeClass('d-none');
        setTimeout(function() {
            categoryCard.css('opacity', 1).css('top', '40px');
        }, 1);
    })
    navLinkHover.on("mouseleave", () => {
        setTimeout(() => {
            categoryCard.addClass('d-none');
        }, 200);
        categoryCard.css('opacity', 0).css('top', '-40px');
    })
</script>