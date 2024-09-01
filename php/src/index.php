<?php
session_start();
require_once __DIR__ . "/lib/util.php";

if ($_SERVER["REQUEST_URI"] == "/index.php") {
    header("Location: /");
    exit;
}
$user_id = isset($_SESSION["user_login"]) ? $_SESSION["user_login"] : (isset($_SESSION["admin_login"]) ? (header("location: ./admin/") && exit) : null);
if ($user_id) {
    $row = sql("SELECT * FROM `users` WHERE `user_id` = ?", [$user_id])->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ขายเสื้อผ้ามือ 2 | <?= $webname; ?></title>
    <?php require_once __DIR__ . "/lib/link.php"; ?>
</head>

<body>
    <?php require_once __DIR__ . "/components/navbar.php"; ?>

    <div style="margin-top: 7.2rem;" class="p-2 pt-0 position-relative">
        <div id="carouselBanner" class="carousel slide position-relative overflow-hidden rounded-4" data-bs-ride="carousel">
            <div class="carousel-indicators" style="gap:1rem">
                <?php foreach ($imageBanner as $index => $imageSrc) { ?>
                    <button style="width: 15px;height: 15px;border-radius: 50%;" type="button" data-bs-target="#carouselBanner" data-bs-slide-to="<?= $index; ?>" <?= $index == 0 ? 'class="active" aria-current="true"' : ""; ?> aria-label="Slide <?= $index + 1; ?>"></button>
                <?php } ?>
            </div>
            <div class="carousel-inner">
                <?php foreach ($imageBanner as $index => $imageSrc) { ?>
                    <div class="carousel-item <?= $index == 0 ? "active" : "" ?>" data-bs-interval="<?= $interval_slide_banner ?>">
                        <img src="<?= $imageSrc; ?>" class="d-block w-100 image-banner" alt="banner <?= $index + 1; ?>" style="object-fit:cover;" draggable="false">
                    </div>
                <?php } ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselBanner" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselBanner" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            <div class="w-100 w-100 h-100 position-absolute top-0 left-0" style="background: linear-gradient(45deg, #414141, transparent);"></div>

            <div class="title-banner">
                <div class="fw-semibold" style="font-size:50px;">
                    <?= $bannerTitle[0]; ?>
                    <div class="fw-medium">
                        <?= $bannerTitle[1]; ?>
                    </div>
                </div>
                <p style="width: 250px;" class="mt-1 fs-5">
                    <?= $bannerTitle[2]; ?>
                </p>
            </div>

        </div>
        <div class="position-absolute d-none d-md-block" style="bottom: -50px;left: 0;">
            <img src="<?= pathImage("cartoon-1.png", "web") ?>" alt="cartoon-1.png" width="250px" height="auto" class="rounded-circle" style="transform: scaleX(-1);">
        </div>
        <div class="position-absolute d-none d-md-block" style="bottom: -50px;right: 0;">
            <img src="<?= pathImage("cartoon-2.png", "web") ?>" alt="cartoon-1.png" width="260px" height="auto" class="rounded-circle">
        </div>
    </div>

    <div class="d-flex gap-3 container imageShowUnderBanner">
        <?php for ($i = 1; $i <= 2; $i++) { ?>
            <div class="bg-light <?= $i == 1 ? 'w-50' : 'w-100' ?> mt-3 rounded-4" style="background-image: url('./assets/images/banner/ads0<?= $i ?>.jpg');background-size: cover;background-repeat: no-repeat;background-size: cover;"></div>
        <?php } ?>
    </div>

    <div class="px-2">
        <div class="bg-primary p-3 my-3 text-center text-white container rounded-4">
            <h1>สินค้าที่คุณกำลังมองหา <i class="fa-solid fa-cart-shopping"></i></h1>
        </div>

        <div class="d-flex justify-content-center flex-column align-items-center d-none" id="productSearchNotFound">
            <div class="d-flex align-items-center justify-content-center h-100 w-100 p-5 pb-0" style="gap: 10px;">
                <h3 class="text-muted">ไม่พบสินค้าที่คุณต้องการค้นหา</h3>
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
            <img src="./assets/images/web/searchNotFoundIcon.png">
        </div>
    </div>

    <div class="container position-relative">
        <?php $categorys = sql("SELECT * FROM `category_products`");
        if ($categorys->rowCount() > 0) { ?>
            <div class="d-flex justify-content-end mb-3 align-items-center">
                <div>
                    <select id="select-category" class="form-select rounded-4">
                        <option value="เลือกหมวดหมู่">เลือกหมวดหมู่</option>
                        <?php while ($category = $categorys->fetch(PDO::FETCH_ASSOC)) { ?>
                            <option <?= isset($_GET["category"]) && $_GET["category"] == $category["cp_id"] ? "selected" : "" ?> value="<?= $category["cp_id"]; ?>"><?= $category["name"]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        <?php } else { ?>
            <div class="text-center text-primary">
                ขณะนี้ยังไม่มีหมวดหมู่...
            </div>
        <?php } ?>

        <div class="row">
            <?php $query = isset($_GET["category"]) ? sql("SELECT `product_id`,`name`,`detail`,`price`,`product_image`,`size`,`cp_id` FROM `products` WHERE `cp_id` = ?", [$_GET["category"]]) : sql("SELECT `product_id`,`name`,`detail`,`price`,`product_image`,`size`,`cp_id` FROM `products`");
            $product_data = $query;
            if ($product_data->rowCount() > 0) {
                while ($product = $product_data->fetch()) { ?>
                    <div data-product-card="<?= $product["product_id"] ?>" class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-12 mb-5">
                        <a href="javascript:void(0)" style="color:var(--bs-primary);text-decoration: none;" data-bs-target="#modalProduct<?= $product["product_id"] ?>" data-bs-toggle="modal">
                            <div class="card-item-order">
                                <img src="<?= pathImage($product["product_image"], "product_images"); ?>" alt="product image" />
                                <div class="p-3 d-flex flex-column justify-content-between" style="height: 160px;">
                                    <div>
                                        <h5 data-product-name="<?= $product["product_id"] ?>" class="titleCardItemOrder"><?= $product["name"]; ?></h5>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2 justify-content-end" style="font-size: 14px;">
                                            <?php foreach (json_decode($product["size"]) as $size) { ?>
                                                <div class="bg-white rounded-4 p-2 py-1">
                                                    <?= $size; ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2 align-items-center">
                                            <h5>฿ <?php echo formatNumberWithComma($product["price"]); ?>.-</h5>
                                            <button type="button" class="addToCart btn">
                                                <i class="fa-solid fa-cart-plus"></i>
                                                <h6>หยิบใส่ตะกร้า</h6>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="modal fade" id="modalProduct<?= $product["product_id"] ?>">
                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                <div class="modal-content rounded-4 shadow-lg border-2" style="border-color:var(--cyan-color1)">
                                    <div class="modal-body position-relative p-5">

                                        <div class="position-absolute" style="top:10px;right:10px">
                                            <button type="button" class="btn rounded-circle btn-light btn-close border-3 border p-2" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="row mt-4 pb-5">
                                            <div class="col-12 col-xl-5 d-flex justify-content-center">
                                                <img src="<?= pathImage($product["product_image"], "product_images") ?>" width="350px" height="350px" class="border object-fit-cover rounded-2">
                                            </div>
                                            <div class="col-12 col-xl-7 mt-5 mt-xl-0">
                                                <div class="d-flex flex-column gap-4">
                                                    <div class="d-flex flex-column">
                                                        <h3><?= $product["name"] ?></h3>
                                                        <h6><?= $product["detail"] ?></h6>
                                                    </div>
                                                    <div class="bg-light p-4 rounded-4" style="width: auto;">
                                                        <h4 class="text-primary fw-semibold">ราคา​ ฿<span><?= $product["price"] ?></span></h4>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <h5>ขนาด:</h5>
                                                        <div class="d-flex flex-row gap-2 flex-wrap">
                                                            <?php foreach (json_decode($product["size"]) as $index => $size) { ?>
                                                                <div>
                                                                    <input hidden type="radio" name="size<?= $product["product_id"] ?>" value="<?= $size; ?>">
                                                                    <button onclick="selectSize($(this))" type="button" class="btn btn-outline-primary rounded-4 check-size" style="width: 100px;"><?= $size; ?></button>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mt-5">
                                                        <h6>จำนวน</h6>
                                                        <div class="d-flex overflow-hidden border rounded-4" style="width: 110px;">
                                                            <button onclick="minusAmountItem($(this).parent().find('.amountItem'))" type="button" class="btn btn-primary border-0 rounded-0 border-0">-</button>
                                                            <input oninput="inputAmountItem($(this))" data-amountitem="<?= $i; ?>" type="number" class="form-control border-0 text-center amountItem" style="width: 50px;box-shadow: none !important;outline: none;" value="1">
                                                            <button onclick="plusAmountItem($(this).parent().find('.amountItem'))" type="button" class="btn btn-primary border-0 rounded-0 border-0" onclick="plusAmountItem(<?= $i; ?>)">+</button>
                                                        </div>
                                                        <a href="./api/manage_carts.php?addToCart&product_id=<?= $product["product_id"] ?>&amount=1&size=noselected" class="btn btn-primary rounded-5 fs-5 ms-auto py-2 px-4 d-flex align-items-center gap-2 addToCart" onclick="checkAddToCart(event,$(this))">
                                                            +<i class="fa-solid fa-cart-shopping"></i>
                                                            <div class="d-none d-lg-block">เพิ่มลงตะกร้าสินค้า</div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <div class="d-flex align-items-center justify-content-center h-100 w-100 p-5 pb-0" style="gap: 10px;">
                    <h3>ขณะนี้ยังไม่มีข้อมูลสินค้า...</h3>
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
                <img class="mb-5" src="<?= pathImage("empty_product.png", "web") ?>" style="width: 100%;height: 350px;object-fit: contain;" draggable="false">
            <?php } ?>
        </div>
    </div>


    <script>
        function searchProduct(query) {
            const product_name = $("[data-product-name]");
            const product_card = $("[data-product-card]");

            product_name.each(function(index, product) {
                const productName = $(product).text().toLowerCase();
                if (productName.includes(query.toLowerCase())) {
                    $(product_card[index]).removeClass("d-none");
                    $("#productSearchNotFound").addClass("d-none");
                } else {
                    $(product_card[index]).addClass("d-none");
                    if ($(product_card).not(".d-none").length === 0) {
                        $("#productSearchNotFound").removeClass("d-none");
                    }
                }
            });
        }

        $("#searchInputNavbar").on("keypress", (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchProduct($("#searchInputNavbar").val())
            }
        })

        function minusAmountItem(input) {
            let amount = parseInt(input.val());
            if (amount > 1) {
                input.val(amount - 1);
                updateHref(input);
            }
        }

        function plusAmountItem(input) {
            let amount = parseInt(input.val());
            input.val(amount + 1);
            updateHref(input);
        }

        function inputAmountItem(input) {
            let amount = parseInt(input.val());
            if (amount >= 1) {
                updateHref(input);
            } else {
                input.val(1);
                updateHref(input);
            }
        }

        function updateHref(input) {
            const amount = parseInt(input.val());
            const href = input.closest(".modal-content").find(".addToCart").attr("href");
            const newHref = href.replace(/amount=\d+/, `amount=${amount}`);
            input.closest(".modal-content").find(".addToCart").attr("href", newHref);
        }

        function selectSize(input) {
            input.closest(".modal-content").find(".check-size").removeClass("active-size");
            const selectedSize = input.siblings("input[type='radio']");
            selectedSize.prop("checked", true).trigger('change');
            if (selectedSize.prop("checked") === true) {
                input.addClass("active-size");
            }
            const href = input.closest(".modal-content").find(".addToCart").attr("href");
            const newHref = href.replace(/size=[^&]*/, `size=${selectedSize.val()}`);
            input.closest(".modal-content").find(".addToCart").attr("href", newHref);
        }

        const checkAddToCart = (e, element) => {
            e.preventDefault();
            const href = $(element).prop("href");
            const url = new URL(href);
            const size = url.searchParams.get("size");
            if (size === "noselected") {
                alert("กรุณาเลือกขนาดก่อนเพิ่มลงตะกร้า!");
            } else {
                window.location.href = href;
            }
        };

        $("#select-category").on("change", function() {
            const selectedCategory = $(this).val();
            localStorage.setItem('scrollPosition', window.scrollY);
            if (selectedCategory !== "เลือกหมวดหมู่") {
                const searchParams = new URLSearchParams(window.location.search);
                searchParams.set('category', selectedCategory);
                window.location.search = searchParams.toString();
            } else {
                window.location.href = "/";
            }
        });
        $(document).ready(function() {
            const scrollPosition = localStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo({
                    top: scrollPosition,
                    behavior: 'auto'
                });
                localStorage.removeItem('scrollPosition');
            }
        });
    </script>
    <?php require_once __DIR__ . "/components/footer.php"; ?>
</body>

</html>