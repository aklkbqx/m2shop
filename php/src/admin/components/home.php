<h1 class="d-flex align-items-center justify-content-start" style="gap:10px"><i class="fa-solid fa-house"></i> หน้าแรก</h1>

<div class="mt-4 p-2 overflow-y-auto overflow-x-hidden" style="height: calc(100% - 15%);">
    <div class="d-flex flex-column gap-2">
        <div class="d-flex">
            <div class="bg-danger rounded-5 p-5 position-relative" style="width: 500px;">
                <div class="d-flex gap-4 align-items-center justify-content-start">
                    <i class="fa-solid fa-user text-white" style="font-size: 50px;"></i>
                    <h2 class="text-white">จำนวนสมาชิก <?= sql("SELECT COUNT(*) AS `countUser` FROM `users` WHERE `role` = 'user'")->fetch()["countUser"] ?> คน</h2>
                </div>
            </div>
        </div>
        <div class="d-flex">
            <div class="bg-warning rounded-5 p-5 position-relative" style="width: 500px;">
                <div class="d-flex gap-4 align-items-center justify-content-start">
                    <i class="fa-solid fa-cart-shopping text-white" style="font-size: 50px;"></i>
                    <h2 class="text-white">จำนวนสินค้า <?= sql("SELECT COUNT(*) AS `countProduct` FROM `products`")->fetch()["countProduct"] ?> รายการ</h2>
                </div>
            </div>
        </div>
    </div>
</div>