<?php
include_once "dateformat.classes.php";
include_once "packages.classes.php";
class ShowPackage extends Packages
{

    // Get Packages

    public function showPackages($class)
    {

        $packageInfo = $this->getPackage($class);

        $i = 0;
        $total = count($packageInfo);

        $packages = "";
        $coupons = "";

        foreach ($packageInfo as $package) {

            if ($i === 0) {
                $packages .= ' <label class="form-label fw-bold text-gray-900 fs-6">Paketinizi Seçin</label> 
                <div class="row fv-row mb-7 fv-plugins-icon-container">';
            }

            $packages .= '
                        <div class="col-xl-6 mb-10">
                            <label role="button">
                            <input type="radio" id="pack" name="pack" value="' . $package['id'] . '">
                            <div class="card card-flush shadow-sm list-group-item-action">
                                <div class="card-body text-center">
                                    <h3 class="mb-5">' . $package['name'] . '</h3>
                                    <div class="text-gray-600 mb-2" id="monthly_fee">Aylık Birim Fiyat: ' . $this->getPackagePrice($package['id'])[0]['monthly_fee'] . '₺ + KDV</div>
                                    <div class="text-gray-600 mb-2" id="subscription_period">Kaç Aylık: ' . $this->getPackagePrice($package['id'])[0]['subscription_period'] . ' </div>
                                </div>
                            </div>
                            </label>
                        </div>

            ';

            if ($i === ($total - 1)) {
                $packages .= '</div>
                ';
            }

            $i++;
        }

        $coupons .= '
				<div class="fv-row mb-5">
                    <div class="fs-6 fw-bold">İndirim Kuponu</div>
                    <div class="text-gray-600 mb-2">Kupon kodu varsa giriniz.</div>
					<input type="text" class="form-control form-control-solid" name="coupon_code" id="coupon_code" placeholder="Kupon Kodu"> 
                    <button type="button" id="apply_coupon" class="btn btn-success mt-5">Kuponu Uygula</button> <button style="display:none" type="button" id="delete_coupon" class="btn btn-danger mt-5">Kuponu Kaldır</button>
				</div>
                ';

        echo json_encode([$packages, $coupons]);
    }


    // Show Total Price

    public function showPrice($id)
    {

        $packageInfo = $this->getPackagePrice($id);

        foreach ($packageInfo as $package) {

            $discount = $package['discount'];
            $monthly_fee = $package['monthly_fee'];
            $subscription_period = $package['subscription_period'];

            $total = $monthly_fee * $subscription_period;

            $getVat = $this->getVat();

            $kdv = $getVat['tax_rate'];
            $vatPercentage = $kdv / 100;

            $priceTotal = '
                        <h2 class="text-black-500 fw-semibold fs-12">Paket Ücreti: <span id="PriceWOVat">' . number_format($total, 2, '.', '') . '</span>₺</h2>
                        <h2 class="text-black-500 fw-semibold fs-12">KDV Oranı: %<span id="vatPercentage">' . $kdv . '</span></h2>
                        <h2 class="text-black-500 fw-semibold fs-12">Toplam Ücret: <span id="PriceWVat">' . number_format(($total + ($total * $vatPercentage)), 2, '.', '') . '</span>₺</h2>
            ';
        }

        echo json_encode(["div" => $priceTotal, "total" => $total, "subscription_period" => $subscription_period, "vat" => $vatPercentage]);
    }

    // Show Cash Discount

    public function getCashDiscountAmount($id)
    {

        $packageInfo = $this->getPackagePrice($id);


        foreach ($packageInfo as $package) {

            $discount = $package['discount'];

            echo json_encode(["status" => "success", "discount" => $discount]);
        }
    }


    // GET Money Transfer Discount

    public function getTransferDiscountInfo()
    {
        $dicountInfo = $this->getTransferDiscount();

        if ($dicountInfo) {
            $discount = $dicountInfo['discount_rate'];
            echo json_encode(["status" => "success", "message" => "% " . $discount . " Havale İndirimi Uygulandı!", "discount" => $discount]);
        } else {
            echo json_encode(["status" => "error", "message" => "İndirim Uygulanamadı!"]);
        }
    }

    // Get Coupon Info Price

    public function getCouponInfo($coupon)
    {
        $couponRes = $this->checkCoupon($coupon);

        if ($couponRes) {

            if ($couponRes['coupon_quantity'] == $couponRes['used_coupon_count']) {
                echo json_encode(["status" => "error", "message" => "Kuponun kullanım hakkı kalmamış!"]);
                return;
            }

            $expireDate = new DateTime($couponRes['coupon_expires']);
            $today = new DateTime();

            $expireDateFormatted = $expireDate->format('Y-m-d');
            $todayFormatted = $today->format('Y-m-d');

            if ($expireDateFormatted < $todayFormatted) {
                echo json_encode(["status" => "error", "message" => "Kuponun Süresi Dolmuş!"]);
                return;
            }

            echo json_encode(["status" => "success", "message" => "Kupon Kodu Uygulandı!", "discount" => $couponRes['discount_value'], "type" => $couponRes['discount_type']]);
        } else {
            echo json_encode(["status" => "error", "message" => "Kupon Kodu Geçersiz!"]);
        }

        /* foreach ($packageInfo as $package) {

            $monthly_fee = $package['monthly_fee'];
            $subscription_period = $package['subscription_period'];

            $total = $monthly_fee * $subscription_period;

            $priceTotal = '
                        <h2 class="text-black-500 fw-semibold fs-12">Toplam Tutar: <span id="totalPrice">' . $total . '</span> ₺</h2>
            ';
        } */

        //echo json_encode([$priceTotal]);
    }
}


class ShowPackagesForAdmin extends PackagesForAdmin
{

    // Get Packages For Admin

    public function showAllPackages()
    {

        $packageInfo = $this->getAllPackages();

        foreach ($packageInfo as $value) {
            $id = htmlspecialchars($value['id']);
            $name = htmlspecialchars($value['name']);
            $monthly_fee = htmlspecialchars($value['monthly_fee']);
            $subscription_period = htmlspecialchars($value['subscription_period']);
            $discount = htmlspecialchars($value['discount']);
            $className = htmlspecialchars($value['className']);
            $status = (int) $value['status'];

            // Duruma göre buton belirle
            if ($status === 0) {
                $statusButton = '
            <a href="javascript:void(0);" class="menu-link px-3 btn btn-primary btn-sm"
               onclick="handleDelete({ id: \'' . $id . '\', url: \'includes/ajax.php?service=activatePackage\' })">
               Aktif Yap
            </a>';
            } else {
                $statusButton = '
            <a href="javascript:void(0);" class="menu-link px-3 btn btn-danger btn-sm"
               onclick="handleDelete({ id: \'' . $id . '\', url: \'includes/ajax.php?service=passivePackage\' })">
               Pasif Yap
            </a>';
            }

            // Tüm satırı birleştir
            $packages = '
        <tr>
            <td>
                <a href="./paket-detay?id=' . $id . '" class="text-gray-800 text-hover-primary mb-1">' . $name . '</a>
            </td>
            <td>' . $monthly_fee . ' ₺</td>
            <td>' . $subscription_period . ' Aylık</td>
            <td>%' . $discount . '</td>
            <td>' . $className . '</td>
            <td style="width:250px;" class="text-end">
                <a href="paket-detay?id=' . $id . '" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                    Görüntüle
                    <i class="fa-regular fa-eye fs-5 ms-1"></i>
                </a>
                ' . $statusButton . '
            </td>
        </tr>';

            echo $packages;
        }
    }


    // Show Package Detail

    public function showOnePackage($id)
    {

        $packageInfo = $this->getOnePackage($id);

        $packageUsers = $this->getPackageUsers($id);

        $packageUsersNo = count($packageUsers);

        $packageList = '
                <div class="mb-3">
                    <h1 class="h3 d-inline align-middle">Böyle bir paket mevcut değil.</h1>
                </div>
        ';

        foreach ($packageInfo as $value) {

            $packageList = '
                <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                    <!--begin::Card-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card body-->
                        <div class="card-body pt-15">
                            <!--begin::Summary-->
                            <div class="d-flex flex-center flex-column mb-5">
                                <!--begin::Avatar-->
                                <!--<div class="symbol symbol-100px symbol-circle mb-7">
                                    <img src="assets/media/avatars/300-1.jpg" alt="image" />
                                </div>-->
                                <!--end::Avatar-->
                                <!--begin::Name-->
                                <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">' . $value['name'] . '</a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="fs-5 fw-semibold text-muted mb-6">' . $value['className'] . '</div>
                                <!--end::Position-->
                                <!--begin::Info-->
                                <div class="d-flex flex-wrap flex-center">
                                    <!--begin::Stats-->
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-75px"> ' . $packageUsersNo . '</span>
                                            <i class="fa-solid fa-users fs-3 text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                        <div class="fw-semibold text-muted"> Paketi Almış Öğrenci Sayısı</div>
                                    </div>
                                    <!--end::Stats-->
                                    
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::Summary-->
                            <!--begin::Details toggle-->
                            <div class="d-flex flex-stack fs-4 py-3">
                                <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_customer_view_details" role="button" aria-expanded="false" aria-controls="kt_customer_view_details">Detaylar
                                    <span class="ms-2 rotate-180">
                                        <i class="ki-duotone ki-down fs-3"></i>
                                    </span>
                                </div>
                                <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Paket bilgilerini güncelle">
                                    <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_update_customer">Güncelle</a>
                                </span>
                            </div>
                            <!--end::Details toggle-->
                            <div class="separator separator-dashed my-3"></div>
                            <!--begin::Details content-->
                            <div id="kt_customer_view_details" class="collapse show">
                                <div class="py-5 fs-6">
                                    <!--begin::Badge-->
                                    <!--<div class="badge badge-light-info d-inline">Premium user</div>-->
                                    <!--end::Badge-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Aylık Ücret</div>
                                    <div class="text-gray-600">
                                        ' . $value['monthly_fee'] . ' ₺
                                    </div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bold mt-5">Abonelik Periyodu</div>
                                    <div class="text-gray-600">' . $value['subscription_period'] . ' Aylık</div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    
                                    <!--end::Details item-->
                                </div>
                            </div>
                            <!--end::Details content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                ';
        }
        echo $packageList;
    }

    // Show Package Detail

    public function showPackageBuyers($id)
    {


        $date = new DateFormat();

        $packageBuyers = $this->getPackageUsers($id);

        foreach ($packageBuyers as $value) {

            $packages = '
                    <tr>
                        <td>
                            <div class="symbol symbol-50px">
                                <img src="assets/media/profile/' . $value['photo'] . '" alt="image" />
                            </div>
                        </td>
                        <td>
                            <a href="ogrenci-detay?id=' . $value['id'] . '" class="text-gray-600 text-hover-primary mb-1">' . $value['name'] . ' ' . $value['surname'] . '</a>
                        </td>
                        <td>
                            ' . $date->changeDate($value['subscribed_end']) . '
                        </td>
                        <td class="pe-0 text-end">
                            <a href="ogrenci-detay?id=' . $value['id'] . '" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Görüntüle
                                <i class="fa-regular fa-eye fs-5 ms-1">
                                </i>
                            </a>
                        </td>
                    </tr>
            ';



            echo $packages;
        }
    }

    public function showUpdatePackage($id)
    {
        $packageInfo = $this->getOnePackage($id);
        $classes = new Classes();
        $classList = $classes->getClasses();

        foreach ($packageInfo as $value) {

            $classOptions = '';
            foreach ($classList as $class) {
                $selected = ($class['id'] == $value['class_id']) ? 'selected' : '';
                $classOptions .= '<option value="' . $class['id'] . '" ' . $selected . '>' . $class['name'] . '</option>';
            }

            $packages = '
            
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_update_customer_header">
                    <h2 class="fw-bold">Paket Güncelle</h2>
                    
                </div>
                <!--end::Modal header-->

                <!--begin::Modal body-->
                <div class="modal-body py-10 px-lg-17">
                    <div class="fw-bold fs-3 rotate collapsible mb-7" data-bs-toggle="collapse" aria-expanded="false" aria-controls="kt_modal_update_customer_user_info">
                        Paket Bilgileri
                    </div>
                    <div>
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Paket Adı</label>
                            <input type="text" id="name" class="form-control form-control-solid" value="' . $value['name'] . '" name="name" />
                            <input type="hidden" name="id" id="id" value="' . $value['id'] . '" />
                        </div>

                        <!-- Sınıf Alanı -->
                        <div class="fv-row mb-7">
                            <label for="packageType" class="required fs-6 fw-semibold mb-2">Sınıf</label>
                            <select class="form-select" id="class_id" name="packageType">
                                <option value="" disabled>Sınıf seçin</option>
                                ' . $classOptions . '
                            </select>
                        </div>

                        <div id="kt_modal_add_customer_billing_info" class="collapse show">
                            <div class="d-flex flex-column mb-7 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Aylık Ücret</label>
                                <input class="form-control form-control-solid" name="monthly_fee" id="monthly_fee" value="' . $value['monthly_fee'] . '" />
                            </div>
                        </div>
                        <div class="mb-3">
                                                                        <label for="subscription_period" class="form-label">Abonelik Periyodu (Ay)</label>
                                                                        <input type="number" class="form-control" id="subscription_period" name="subscription_period" placeholder="Abonelik periyodu giriniz." value="' . $value['subscription_period'] . '">
                                                                    </div>
                    </div>
                </div>
                <!--end::Modal body-->

                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <button type="reset" id="kt_modal_update_customer_cancel" class="btn btn-light btn-sm me-3">İptal</button>
                    <button  id="packageUpdate" class="btn btn-primary btn-sm">
                        <span class="indicator-label">Gönder</span>
                        <span class="indicator-progress">Lütfen bekleyin...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
                <!--end::Modal footer-->
            
        ';

            echo $packages;
        }
    }
}
