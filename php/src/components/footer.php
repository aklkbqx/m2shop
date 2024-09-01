<footer class="mt-5">
    <div class="p-4" style="background-image: linear-gradient(180deg, var(--bs-primary), var(--bs-primary-deep));">
        <div class="container d-flex justify-content-between mb-4">
            <div class="d-flex flex-column gap-3">
                <h1 class="text-white me-2"><?= $webname; ?></h1>
                <div class="d-flex flex-column text-white">
                    <div><i class="fa-solid fa-map-pin"></i> ที่อยู่: <?= $address_var ?></div>
                    <div><i class="fa-solid fa-envelope"></i> อีเมล​: <?= $email_var ?></div>
                    <div><i class="fa-solid fa-phone"></i> เบอร์โทรศัพท์: <?= $tel_var ?></div>
                </div>
            </div>
            <div class="text-white d-none d-sm-block">
                ติดตามข่าวสารจากเรา
                <div class="d-flex align-items-center gap-2">
                    <input type="email" class="form-control rounded-4" placeholder="อีเมล" id="email-input-subscribe">
                    <button id="btnsubscribe" type="button" class="btn btn-primary rounded-4 border">ติดตาม</button>
                </div>
            </div>
        </div>
        <h5 class="text-center my-3 text-white">ช่องทางการติดต่อ</h5>
        <div class="flex-row gap-4 d-flex align-items-center justify-content-center">
            <?php $linkContact = [
                [
                    "label" => "Facebook",
                    "icon" => '<i class="fa-brands fa-facebook"></i>',
                    "link" => "https://web.facebook.com/facebook/"
                ],
                [
                    "label" => "Instagram",
                    "icon" => '<i class="fa-brands fa-square-instagram"></i>',
                    "link" => "https://www.instagram.com/instagram"
                ],
                [
                    "label" => "Line",
                    "icon" => '<i class="fa-brands fa-line"></i>',
                    "link" => "https://line.me/th/"
                ],
                [
                    "label" => "เบอร์โทรศัพท์",
                    "icon" => '<i class="fa-solid fa-phone"></i>',
                    "link" => "#"
                ],
            ];
            foreach ($linkContact as $index => $link) { ?>
                <div class="d-flex align-items-center flex-column gap-2">
                    <a href="<?= $link["link"] ?>" target="_blank" class="rounded-circle bg-white d-flex align-items-center justify-content-center text-primary fs-2" style="width: 50px; height: 50px;">
                        <?= $link["icon"] ?>
                    </a>
                    <h6 class="text-white" style="font-size: 13px;"><?= $link["label"] ?></h6>
                </div>
            <?php } ?>
        </div>
        <div class="text-center text-white mt-5">
            <?= $copyright_text_var; ?>
        </div>
    </div>
</footer>

<script>
    const btnsubscribe = $("#btnsubscribe");

    const handleSubscribe = () => {
        const emailInputSubscribe = $("#email-input-subscribe");
        if (emailInputSubscribe.val().trim() === "") {
            alert("กรุณากรอกอีเมลก่อนสมัครสมาชิก");
        } else {
            alert("ขอบคุณที่สมัครเป็นสมาชิกรับข่าวสารจากทางเรา🙏🏻");
        }
    };

    btnsubscribe.on("click", handleSubscribe);
</script>