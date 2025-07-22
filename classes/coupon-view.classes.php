<?php
    include_once "dateformat.classes.php";

    class ShowCoupons extends Coupon
    {

        public function getCouponList()
        {

            $couponInfo = $this->getAllCoupon();

            $dateFormat = new DateFormat();

            foreach ($couponInfo as $key => $value) {

                $discountType = "TL";
                if ($value['discount_type'] == "percentage") {
                    $discountType = "Yüzde";
                }

                $couponList = '
                <tr>
                    <td>
                       <a href="./kupon-detay?id=' . $value['id'] . '" class="text-gray-800 text-hover-primary mb-1"> ' . $value['coupon_code'] . ' </a>
                   </td>
                   <td>
                       ' . $discountType . '
                   </td>
                   <td>
                       ' . $value['discount_value'] . '
                   </td>
                   <td>
                       ' . $value['is_active'] . '
                   </td>
                   <td>' . $dateFormat->changeDate($value['coupon_expires']) . '</td>
                   <td>' . $value['coupon_quantity'] . '</td>
                   <td>' . $value['used_coupon_count'] . '</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                        <!--begin::Menu-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                            data-kt-menu="true">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="./kupon-detay?id=' . $value['id'] . '" class="menu-link px-3">Görüntüle</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3 delete_coupon" data-id=' . $value['id'] . '" data-kt-coupon-table-filter="delete_row">Pasif Yap</a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu-->
                    </td>
                </tr>
                ';
                echo $couponList;
            }
        }
    }
