<?php
if (!isset($userInfo)) {
    throw new Exception("userInfo not provided");
}

if (empty($userInfo['photo'])) {
    $photoPath = 'assets/media/avatars/blank.png';
} else {
    $photoPath = 'assets/media/profile/' . $userInfo['photo'];
}
?>


<div class="d-flex flex-column align-items-center flex-sm-nowrap">
    <div class="me-7 mb-4">
        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
            <img src="<?= $photoPath ?>" alt="image" />
            <!-- <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div> -->
        </div>
    </div>
    <div class="flex-grow-1 w-100">
        <div class="justify-content-center align-items-center flex-wrap mb-2">
            <div class="d-flex flex-column">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <a href="#"
                        class="text-gray-900 text-hover-primary fs-2 fw-bold me-1"><?php echo $userInfo['name'] . ' ' . $userInfo['surname'] ?>
                    </a>
                </div>
                <hr>
                <div class="d-flex flex-wrap justify-content-center fw-semibold fs-6 pe-2">
                    <span class="d-flex flex-column align-items-center text-gray-500 mb-2">
                        <i class="fa-solid fa-school fs-4 me-1"></i>

                        <?php if (isset($userInfo['className'])) {
                            $spanText = $userInfo['className'] ?? "-";
                           /*  echo ' 
                            <span class="d-flex align-items-center text-gray-500 me-5 mb-2">
                                <i class="fa-solid fa-table fs-4 me-1"></i>
                                ' . $spanText . '
                            </span>'; */
                        }
                        ?>
                        <?= $schoolInfo['name'] . ' / ' .  $spanText ?? '-' ?>
                    </span>
                    </span>
                    <?php if (isset($userInfo['lessonName'])) {
                        $spanText = $userInfo['lessonName'] ?? "-";
                        echo ' 
                        <span class="d-flex align-items-center text-gray-500 me-5 mb-2">
                            <i class="fa-solid fa-table fs-4 me-1"></i>
                            ' . $spanText . '
                        </span>';
                    }
                    ?>
                </div>

                <!-- <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2 mt-3 mb-2">

                    <div class="d-flex justify-content-end " data-kt-customer-table-toolbar="base">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_update_customer">Bilgilerimi
                            Güncelle</button>
                    </div>
                    <div class="d-flex justify-content-end ms-8" data-kt-customer-table-toolbar="base">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_update_password">Şifremi
                            Güncelle</button>
                    </div>
                    <div class="d-flex justify-content-end ms-8" data-kt-customer-table-toolbar="base">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_update_email">E-Posta'mı
                            Değiştir</button>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>