<form id="kt_modal_update_role_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#" data-kt-redirect="kullanici-gruplari">
    <!--begin::Scroll-->
    <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_role_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_role_header" data-kt-scroll-wrappers="#kt_modal_update_role_scroll" data-kt-scroll-offset="300px" style="max-height: 645px;">
        <!--begin::Input group-->
        <div class="fv-row mb-10 fv-plugins-icon-container">
            <!--begin::Label-->
            <label class="fs-5 fw-bold form-label mb-2">
                Öğrenciler
            </label>
            <!--end::Label-->
            <!--begin::Input-->
            <input type="hidden" class="form-control form-control-solid" placeholder="Enter a role name" name="role_id" value="2">
            <!--end::Input-->
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
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

                        <!--begin::Table row-->
                        <tr>
                            <!--begin::Label-->
                            <td class="text-gray-800">Kullanıcı Yönetimi</td>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <td>
                                <!--begin::Wrapper-->
                                <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                        <input class="form-check-input" type="checkbox" value="25" name="roleCheck[]">
                                    </label>
                                    <!--end::Checkbox-->
                                </div>
                                <!--end::Wrapper-->
                            </td>
                            <!--end::Input group-->
                        </tr>
                        <!--end::Table row-->

                        <!--begin::Table row-->
                        <tr>
                            <!--begin::Label-->
                            <td class="text-gray-800">Ders ve İçerik Yönetimi</td>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <td>
                                <!--begin::Wrapper-->
                                <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                        <input class="form-check-input" type="checkbox" checked="" value="26" name="roleCheck[]">
                                    </label>
                                    <!--end::Checkbox-->
                                </div>
                                <!--end::Wrapper-->
                            </td>
                            <!--end::Input group-->
                        </tr>
                        <!--end::Table row-->

                        <!--begin::Table row-->
                        <tr>
                            <!--begin::Label-->
                            <td class="text-gray-800">İlerleme ve Performans Takibi</td>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <td>
                                <!--begin::Wrapper-->
                                <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                        <input class="form-check-input" type="checkbox" checked="" value="27" name="roleCheck[]">
                                    </label>
                                    <!--end::Checkbox-->
                                </div>
                                <!--end::Wrapper-->
                            </td>
                            <!--end::Input group-->
                        </tr>
                        <!--end::Table row-->

                        <!--begin::Table row-->
                        <tr>
                            <!--begin::Label-->
                            <td class="text-gray-800">Sistem Bildirimleri ve Duyurular</td>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <td>
                                <!--begin::Wrapper-->
                                <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                        <input class="form-check-input" type="checkbox" checked="" value="28" name="roleCheck[]">
                                    </label>
                                    <!--end::Checkbox-->
                                </div>
                                <!--end::Wrapper-->
                            </td>
                            <!--end::Input group-->
                        </tr>
                        <!--end::Table row-->

                        <!--begin::Table row-->
                        <tr>
                            <!--begin::Label-->
                            <td class="text-gray-800">Raporlama ve Analitik</td>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <td>
                                <!--begin::Wrapper-->
                                <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                        <input class="form-check-input" type="checkbox" checked="" value="29" name="roleCheck[]">
                                    </label>
                                    <!--end::Checkbox-->
                                </div>
                                <!--end::Wrapper-->
                            </td>
                            <!--end::Input group-->
                        </tr>
                        <!--end::Table row-->

                        <!--begin::Table row-->
                        <tr>
                            <!--begin::Label-->
                            <td class="text-gray-800">Sistem ve Güvenlik Yönetimi</td>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <td>
                                <!--begin::Wrapper-->
                                <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                        <input class="form-check-input" type="checkbox" value="30" name="roleCheck[]">
                                    </label>
                                    <!--end::Checkbox-->
                                </div>
                                <!--end::Wrapper-->
                            </td>
                            <!--end::Input group-->
                        </tr>
                        <!--end::Table row-->

                        <!--begin::Table row-->
                        <tr>
                            <!--begin::Label-->
                            <td class="text-gray-800">Ödev ve Test Yönetimi</td>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <td>
                                <!--begin::Wrapper-->
                                <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                        <input class="form-check-input" type="checkbox" checked="" value="31" name="roleCheck[]">
                                    </label>
                                    <!--end::Checkbox-->
                                </div>
                                <!--end::Wrapper-->
                            </td>
                            <!--end::Input group-->
                        </tr>
                        <!--end::Table row-->

                        <!--begin::Table row-->
                        <tr>
                            <!--begin::Label-->
                            <td class="text-gray-800">Destek ve Geri Bildirim Yönetimi</td>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <td>
                                <!--begin::Wrapper-->
                                <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                        <input class="form-check-input" type="checkbox" checked="" value="32" name="roleCheck[]">
                                    </label>
                                    <!--end::Checkbox-->
                                </div>
                                <!--end::Wrapper-->
                            </td>
                            <!--end::Input group-->
                        </tr>
                        <!--end::Table row-->

                        <!--begin::Table row-->
                        <tr>
                            <!--begin::Label-->
                            <td class="text-gray-800">İçerik Yükleme ve Depolama Yönetimi</td>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <td>
                                <!--begin::Wrapper-->
                                <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                        <input class="form-check-input" type="checkbox" value="33" name="roleCheck[]">
                                    </label>
                                    <!--end::Checkbox-->
                                </div>
                                <!--end::Wrapper-->
                            </td>
                            <!--end::Input group-->
                        </tr>
                        <!--end::Table row-->

                        <!--begin::Table row-->
                        <tr>
                            <!--begin::Label-->
                            <td class="text-gray-800">Entegrasyon ve API Yönetimi</td>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <td>
                                <!--begin::Wrapper-->
                                <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                        <input class="form-check-input" type="checkbox" value="34" name="roleCheck[]">
                                    </label>
                                    <!--end::Checkbox-->
                                </div>
                                <!--end::Wrapper-->
                            </td>
                            <!--end::Input group-->
                        </tr>
                        <!--end::Table row-->

                        <!--begin::Table row-->
                        <tr>
                            <!--begin::Label-->
                            <td class="text-gray-800">Muhasebe Paneli</td>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <td>
                                <!--begin::Wrapper-->
                                <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                        <input class="form-check-input" type="checkbox" value="35" name="roleCheck[]">
                                    </label>
                                    <!--end::Checkbox-->
                                </div>
                                <!--end::Wrapper-->
                            </td>
                            <!--end::Input group-->
                        </tr>
                        <!--end::Table row-->

                        <!--begin::Table row-->
                        <tr>
                            <!--begin::Label-->
                            <td class="text-gray-800">Teknik Servis Paneli</td>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <td>
                                <!--begin::Wrapper-->
                                <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                        <input class="form-check-input" type="checkbox" value="36" name="roleCheck[]">
                                    </label>
                                    <!--end::Checkbox-->
                                </div>
                                <!--end::Wrapper-->
                            </td>
                            <!--end::Input group-->
                        </tr>
                        <!--end::Table row-->

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