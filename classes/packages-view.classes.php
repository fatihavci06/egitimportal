<?php
include_once "dateformat.classes.php";

class ShowPackage extends Packages
{

    // Get Units For Topic List

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
                            <img class="mw-100" src="assets/media/paketler/' . $package['image'] . '">
                            </label>
                        </div>

            ';

            if ($i === ($total - 1)) {
                $packages .= '</div>';
            }

            $i++;
        }

        $coupons .= '
				<div class="fv-row mb-5">
					<input type="text" class="form-control" name="coupon_code" id="coupon_code" placeholder="Kupon Kodunu Girin">
				</div>
                ';

        echo json_encode([$packages, $coupons]);
    }
}
