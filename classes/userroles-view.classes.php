<?php
include_once "dateformat.classes.php";

class ShowRole extends Roles
{

    // Get Roles

    public function getRoleList()
    {

        $roleInfo = $this->getRolesList();

        foreach ($roleInfo as $key => $value) {

            $roleList = '
                    <option value="' . $value['id'] . '">' . $value['name'] . '</option>
                ';
            echo $roleList;
        }
    }

    // Get Roles List

    public function getRolesDataList()
    {

        $roleInfo = $this->getRolesList();

        foreach ($roleInfo as $key => $value) {

            $userNumber = $this->getRolesUserNumber($value['id']);

            $roleList = '
                    <tr>
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="' . $value['slug'] . '" />
                            </div>
                        </td>
                        <td>
                            <a href="./ogrenci-detay/' . $value['slug'] . '" class="text-gray-800 text-hover-primary mb-1">' . $value['name'] . '</a>
                        </td>
                        <td>
                            ' . $userNumber . '
                        </td>
                        <td class="text-end">
                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="./ogrenci-detay/' . $value['slug'] . '" class="menu-link px-3">Görüntüle</a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-customer-table-filter="delete_row">Pasif Yap</a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                ';
            echo $roleList;
        }
    }

    // Get Roles List

    public function getRolesDataList2()
    {

        $roleInfo = $this->getRolesList();

        $roleDetails = $this->getRolesDetail();

        foreach ($roleInfo as $key => $value) {

            $roles = "";

            $userNumber = $this->getRolesUserNumber($value['id']);

            foreach ($roleDetails as $key => $valueRole) {
                $rolesId = explode(",", $valueRole['role']);
                if (in_array($value['id'], $rolesId)) {
                    $roles .= '
                        <div class="d-flex align-items-center py-2">
                            <span class="bullet bg-primary me-3"></span> ' . $valueRole['name'] . '
                        </div>
                    ';
                }
            }

            $roleList = '
                <!--begin::Col-->
                    <div class="col-md-4">
                        <!--begin::Card-->
                        <div class="card card-flush h-md-100">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>' . $value['name'] . '</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-1">
                                <!--begin::Users-->
                                <div class="fw-bold text-gray-600 mb-5">Bu yetkideki kullanıcı sayısı: ' . $userNumber . '</div>
                                <!--end::Users-->
                                <!--begin::Permissions-->
                                <div class="d-flex flex-column text-gray-600">
                                    ' . $roles . '
                                </div>
                                <!--end::Permissions-->
                            </div>
                            <!--end::Card body-->
                            <!--begin::Card footer-->
                            <div class="card-footer flex-wrap pt-0">
                                <a href="kullanici-grup-detay?q=' . $value['slug'] . '" class="btn btn-light btn-active-primary my-1 me-2">Yetkiyi İncele</a>
                            </div>
                            <!--end::Card footer-->
                        </div>
                        <!--end::Card-->
                    </div>
				<!--end::Col-->
                ';
            echo $roleList;
        }
    }

    // GET Role Details

    public function getRoleDetail($slug){

        $dateFormat = new DateFormat();

        $roleInfo = $this->getOneRole($slug);

        if(empty($roleInfo)){
            echo "Böyle bir kullanıcı grubu yok!";
        }else{

            $roles = "";

            $users = "";

            $rolesPermission = "";

            $userNumber = $this->getRolesUserNumber($roleInfo['id']);

            $usersInfo = $this->getRoleUsers($roleInfo['id']);

            $roleDetails = $this->getRolesDetail();

            foreach ($roleDetails as $key => $valueRole) {
                $rolesId = explode(",", $valueRole['role']);

                $checked = "";

                if (in_array($roleInfo['id'], $rolesId)) {
                    $roles .= '
                        <div class="d-flex align-items-center py-2">
                            <span class="bullet bg-primary me-3"></span> ' . $valueRole['name'] . '
                        </div>
                    ';
                    $checked = "checked";
                }

                $rolesPermission .= '
                    <!--begin::Table row-->
                        <tr>
                            <!--begin::Label-->
                            <td class="text-gray-800">' . $valueRole['name'] . '</td>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <td>
                                <!--begin::Wrapper-->
                                <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                        <input class="form-check-input" type="checkbox" ' . $checked . ' value="' . $valueRole['id'] . '" name="roleCheck[]" />
                                    </label>
                                    <!--end::Checkbox-->
                                </div>
                                <!--end::Wrapper-->
                            </td>
                            <!--end::Input group-->
                        </tr>
                    <!--end::Table row-->
                ';

            }

            

            foreach ($usersInfo as $key => $userValue) {
                $users .= '
                    <tr>
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="' . $userValue['id'] . '" />
                            </div>
                        </td>
                        <td>' . $userValue['id'] . '</td>
                        <td class="d-flex align-items-center">
                            <!--begin:: Avatar -->
                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <a href="apps/user-management/users/view.html">
                                    <div class="symbol-label">
                                        <img src="assets/media/profile/' . $userValue['photo'] . '" alt="' . $userValue['name'] . ' ' . $userValue['surname'] . '" class="w-100" />
                                    </div>
                                </a>
                            </div>
                            <!--end::Avatar-->
                            <!--begin::User details-->
                            <div class="d-flex flex-column">
                                <a href="apps/user-management/users/view.html" class="text-gray-800 text-hover-primary mb-1">' . $userValue['name'] . ' ' . $userValue['surname'] . '</a>
                                <span><a href="mailto:' . $userValue['email'] . '">' . $userValue['email'] . '</span>
                            </div>
                            <!--begin::User details-->
                        </td>
                        <td>' . $dateFormat->changeDateHour($userValue['created_at']) . '</td>
                        <td class="text-end">
                            <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">İşlemler
                                <i class="ki-duotone ki-down fs-5 m-0"></i></a>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="apps/user-management/users/view.html" class="menu-link px-3">Görüntüle</a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-roles-table-filter="delete_row">Pasif Yap</a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                        </td>
                    </tr>
                ';
            }

            $roleList = '
                <div class="d-flex flex-column flex-lg-row">
                    <!--begin::Sidebar-->
                    <div class="flex-column flex-lg-row-auto w-100 w-lg-200px w-xl-300px mb-10">
                        <!--begin::Card-->
                        <div class="card card-flush">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2 class="mb-0">Yetkileri</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Permissions-->
                                <div class="d-flex flex-column text-gray-600">
                                    ' . $roles . '
                                </div>
                                <!--end::Permissions-->
                            </div>
                            <!--end::Card body-->
                            <!--begin::Card footer-->
                            <div class="card-footer pt-0">
                                <button type="button" class="btn btn-light btn-active-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_update_role">Yetkileri Düzenle</button>
                            </div>
                            <!--end::Card footer-->
                        </div>
                        <!--end::Card-->
                        <!--begin::Modal-->
                        <!--begin::Modal - Update role-->
                        <div class="modal fade" id="kt_modal_update_role" tabindex="-1" aria-hidden="true">
                            <!--begin::Modal dialog-->
                            <div class="modal-dialog modal-dialog-centered mw-750px">
                                <!--begin::Modal content-->
                                <div class="modal-content">
                                    <!--begin::Modal header-->
                                    <div class="modal-header">
                                        <!--begin::Modal title-->
                                        <h2 class="fw-bold">Yetki Güncelle</h2>
                                        <!--end::Modal title-->
                                        <!--begin::Close-->
                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-roles-modal-action="close">
                                            <i class="ki-duotone ki-cross fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                        <!--end::Close-->
                                    </div>
                                    <!--end::Modal header-->
                                    <!--begin::Modal body-->
                                    <div class="modal-body scroll-y mx-5 my-7">
                                        <!--begin::Form-->
                                        <form id="kt_modal_update_role_form" class="form" action="#" data-kt-redirect="kullanici-gruplari">
                                            <!--begin::Scroll-->
                                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_role_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_role_header" data-kt-scroll-wrappers="#kt_modal_update_role_scroll" data-kt-scroll-offset="300px">
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-10">
                                                    <!--begin::Label-->
                                                    <label class="fs-5 fw-bold form-label mb-2">
                                                        ' . $roleInfo['name'] . '
                                                    </label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input type="hidden" class="form-control form-control-solid" placeholder="Enter a role name" name="role_id" value="'. $roleInfo['id'] .'" />
                                                    <!--end::Input-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Permissions-->
                                                <div class="fv-row">
                                                    <!--begin::Label-->
                                                    <label class="fs-5 fw-bold form-label mb-2">Yetkiler</label>
                                                    <!--end::Label-->
                                                    <!--begin::Table wrapper-->
                                                    <div class="table-responsive">
                                                        <!--begin::Table-->
                                                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                                                            <!--begin::Table body-->
                                                            <tbody class="text-gray-600 fw-semibold">
                                                                ' . $rolesPermission . '
                                                            </tbody>
                                                            <!--end::Table body-->
                                                        </table>
                                                        <!--end::Table-->
                                                    </div>
                                                    <!--end::Table wrapper-->
                                                </div>
                                                <!--end::Permissions-->
                                            </div>
                                            <!--end::Scroll-->
                                            <!--begin::Actions-->
                                            <div class="text-center pt-15">
                                                <button type="reset" class="btn btn-light me-3" data-kt-roles-modal-action="cancel">İptal</button>
                                                <button type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
                                                    <span class="indicator-label">Gönder</span>
                                                    <span class="indicator-progress">Lütfen Bekleyin...
                                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                            </div>
                                            <!--end::Actions-->
                                        </form>
                                        <!--end::Form-->
                                    </div>
                                    <!--end::Modal body-->
                                </div>
                                <!--end::Modal content-->
                            </div>
                            <!--end::Modal dialog-->
                        </div>
                        <!--end::Modal - Update role-->
                        <!--end::Modal-->
                    </div>
                    <!--end::Sidebar-->
                    <!--begin::Content-->
                    <div class="flex-lg-row-fluid ms-lg-10">
                        <!--begin::Card-->
                        <div class="card card-flush mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header pt-5">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2 class="d-flex align-items-center">Kullanıcılar
                                        <span class="text-gray-600 fs-6 ms-1">(' . $userNumber . ')</span>
                                    </h2>
                                </div>
                                <!--end::Card title-->
                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Search-->
                                    <div class="d-flex align-items-center position-relative my-1" data-kt-view-roles-table-toolbar="base">
                                        <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <input type="text" data-kt-roles-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Kullanıcı Ara" />
                                    </div>
                                    <!--end::Search-->
                                    <!--begin::Group actions-->
                                    <div class="d-flex justify-content-end align-items-center d-none" data-kt-view-roles-table-toolbar="selected">
                                        <div class="fw-bold me-5">
                                            <span class="me-2" data-kt-view-roles-table-select="selected_count"></span>Selected
                                        </div>
                                        <button type="button" class="btn btn-danger" data-kt-view-roles-table-select="delete_selected">Delete Selected</button>
                                    </div>
                                    <!--end::Group actions-->
                                </div>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_roles_view_table">
                                    <thead>
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <th class="w-10px pe-2">
                                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_roles_view_table .form-check-input" value="1" />
                                                </div>
                                            </th>
                                            <th class="min-w-50px">ID</th>
                                            <th class="min-w-150px">Kullanıcı</th>
                                            <th class="min-w-125px">Kayıt Tarihi</th>
                                            <th class="text-end min-w-100px">İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        ' . $users . '
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Content-->
                </div>
                ';
            echo $roleList;
        }

    }

}
