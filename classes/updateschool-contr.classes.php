<?php

include_once 'createpassword.classes.php';
include_once 'adduser.classes.php';
include_once 'Mailer.php';

class UpdateSchoolContr extends UpdateSchool
{

    private $old_slug;
    private $name;
    private $address;
    private $district;
    private $postcode;
    private $city;
    private $email;
    private $email_old;
    private $telephone;
    private $schoolAdminName;
    private $schoolAdminSurname;
    private $schoolAdminEmail;
    private $schoolAdminTelephone;
    private $schoolCoordinatorName;
    private $schoolCoordinatorSurname;
    private $schoolCoordinatorEmail;
    private $schoolCoordinatorTelephone;
    private $schId;
    private $old_admin_email;
    private $old_coord_email;
    private $old_admin_name;
    private $old_admin_surname;
    private $old_coord_name;
    private $old_coord_surname;

    public function __construct($old_slug, $name, $address, $district, $postcode, $city, $email, $email_old, $telephone, $schoolAdminName, $schoolAdminSurname, $schoolAdminEmail, $schoolAdminTelephone, $schoolCoordinatorName, $schoolCoordinatorSurname, $schoolCoordinatorEmail, $schoolCoordinatorTelephone, $schId, $old_admin_email, $old_coord_email, $old_admin_name, $old_admin_surname, $old_coord_name, $old_coord_surname)
    {
        $this->old_slug = $old_slug;
        $this->name = $name;
        $this->address = $address;
        $this->district = $district;
        $this->postcode = $postcode;
        $this->city = $city;
        $this->email = $email;
        $this->email_old = $email_old;
        $this->telephone = $telephone;
        $this->schoolAdminName = $schoolAdminName;
        $this->schoolAdminSurname = $schoolAdminSurname;
        $this->schoolAdminEmail = $schoolAdminEmail;
        $this->schoolAdminTelephone = $schoolAdminTelephone;
        $this->schoolCoordinatorName = $schoolCoordinatorName;
        $this->schoolCoordinatorSurname = $schoolCoordinatorSurname;
        $this->schoolCoordinatorEmail = $schoolCoordinatorEmail;
        $this->schoolCoordinatorTelephone = $schoolCoordinatorTelephone;
        $this->schId = $schId;
        $this->old_admin_email = $old_admin_email;
        $this->old_coord_email = $old_coord_email;
        $this->old_admin_name = $old_admin_name;
        $this->old_admin_surname = $old_admin_surname;
        $this->old_coord_name = $old_coord_name;
        $this->old_coord_surname = $old_coord_surname;
    }

    public function updateSchoolDb()
    {
        $slugName = new Slug($this->name);
        $slug = $slugName->slugify($this->name);

        if ($slug == $this->old_slug) {
            $slug = $this->old_slug;
        } else {
            $Check = new UpdateSchool();
            $slugRes = $Check->checkSlug($slug);

            if (count($slugRes) > 0) {
                $ech = end($slugRes);

                $output = substr($ech['slug'], -1, strrpos($ech['slug'], '-'));

                if (!is_numeric($output)) {
                    $output = 1;
                } else {
                    $output = $output + 1;
                }

                $slug = $slug . "-" . $output;
            } else {
                $slug = $slug;
            }
        }

        if ($this->email == $this->email_old) {
        } else {
            $Check = new UpdateSchool();
            $emailRes = $Check->checkEmail($this->email);

            if (count($emailRes) > 0) {
                echo json_encode(["status" => "error", "message" => "Hata: Bu e-posta daha önce kullanılmış!"]);
                die();
            }
        }

        $usersInfo = new AddUser();

        if (!empty($this->schoolAdminEmail)) {
            if ($this->schoolAdminEmail == $this->old_admin_email) {
                // E-posta değişmemiş, kontrol etmeye gerek yok
            } else {
                $emailRes2 = $usersInfo->checkEmail($this->schoolAdminEmail);

                if (count($emailRes2) > 0) {
                    echo json_encode(["status" => "error", "message" => "Hata: Bu admin e-postası daha önce kullanılmış!"]);
                    die();
                }
            }
        }

        if (!empty($this->schoolCoordinatorEmail)) {
            if ($this->schoolCoordinatorEmail == $this->old_coord_email) {
                // E-posta değişmemiş, kontrol etmeye gerek yok
            } else {
                $emailRes3 = $usersInfo->checkEmail($this->schoolCoordinatorEmail);

                if (count($emailRes3) > 0) {
                    echo json_encode(["status" => "error", "message" => "Hata: Bu koordinatör e-postası daha önce kullanılmış!"]);
                    die();
                }
            }
        }

        $slugAdmin = "";
        $slugCoordinator = "";
        $schoolAdminPasswordHash = "";
        $schoolCoordinatorPasswordHash = "";

        if (!empty($this->schoolAdminName) && !empty($this->schoolAdminSurname)) {
            if (($this->schoolAdminName == $this->old_admin_name) OR ($this->schoolAdminSurname == $this->old_admin_surname)) {
                $slugAdminName = new Slug($this->schoolAdminName);
                $slugAdminName = $slugAdminName->slugify($this->schoolAdminName);

                $slugAdminSurname = new Slug($this->schoolAdminSurname);
                $slugAdminSurname = $slugAdminSurname->slugify($this->schoolAdminSurname);

                $slugAdmin = $slugAdminName . "-" . $slugAdminSurname;
                $slugAdminRes = $usersInfo->checkUsername($slugAdmin);
                if (count($slugAdminRes) > 0) {
                    $ech = end($slugAdminRes);

                    $output = substr($ech['username'], -1, strrpos($ech['username'], '-'));

                    if (!is_numeric($output)) {
                        $output = 1;
                    } else {
                        $output = $output + 1;
                    }

                    $slugAdmin = $slugAdmin . "-" . $output;
                } else {
                    $slugAdmin = $slugAdmin;
                }
            } else {
                $slugAdminName = new Slug($this->schoolAdminName);
                $slugAdminName = $slugAdminName->slugify($this->schoolAdminName);

                $slugAdminSurname = new Slug($this->schoolAdminSurname);
                $slugAdminSurname = $slugAdminSurname->slugify($this->schoolAdminSurname);

                $slugAdmin = $slugAdminName . "-" . $slugAdminSurname;
                $slugAdminRes = $usersInfo->checkUsername($slugAdmin);
                if (count($slugAdminRes) > 0) {
                    $ech = end($slugAdminRes);

                    $output = substr($ech['username'], -1, strrpos($ech['username'], '-'));

                    if (!is_numeric($output)) {
                        $output = 1;
                    } else {
                        $output = $output + 1;
                    }

                    $slugAdmin = $slugAdmin . "-" . $output;
                } else {
                    $slugAdmin = $slugAdmin;
                }
            }
        }

        if (!empty($this->schoolCoordinatorName) && !empty($this->schoolCoordinatorSurname)) {
            if (($this->schoolCoordinatorName == $this->old_coord_name) && ($this->schoolCoordinatorSurname == $this->old_coord_surname)) {
                $slugCoordinatorName = new Slug($this->schoolCoordinatorName);
                $slugCoordinatorName = $slugCoordinatorName->slugify($this->schoolCoordinatorName);

                $slugCoordinatorSurname = new Slug($this->schoolCoordinatorSurname);
                $slugCoordinatorSurname = $slugCoordinatorSurname->slugify($this->schoolCoordinatorSurname);

                $slugCoordinator = $slugCoordinatorName . "-" . $slugCoordinatorSurname;
                $slugCoordinatorRes = $usersInfo->checkUsername($slugCoordinator);
                if (count($slugCoordinatorRes) > 0) {
                    $ech = end($slugCoordinatorRes);

                    $output = substr($ech['slug'], -1, strrpos($ech['slug'], '-'));

                    if (!is_numeric($output)) {
                        $output = 1;
                    } else {
                        $output = $output + 1;
                    }

                    $slugCoordinator = $slugCoordinator . "-" . $output;
                } else {
                    $slugCoordinator = $slugCoordinator;
                }
            } else {
                $slugCoordinatorName = new Slug($this->schoolCoordinatorName);
                $slugCoordinatorName = $slugCoordinatorName->slugify($this->schoolCoordinatorName);

                $slugCoordinatorSurname = new Slug($this->schoolCoordinatorSurname);
                $slugCoordinatorSurname = $slugCoordinatorSurname->slugify($this->schoolCoordinatorSurname);

                $slugCoordinator = $slugCoordinatorName . "-" . $slugCoordinatorSurname;
                $slugCoordinatorRes = $usersInfo->checkUsername($slugCoordinator);
                if (count($slugCoordinatorRes) > 0) {
                    $ech = end($slugCoordinatorRes);

                    $output = substr($ech['slug'], -1, strrpos($ech['slug'], '-'));

                    if (!is_numeric($output)) {
                        $output = 1;
                    } else {
                        $output = $output + 1;
                    }

                    $slugCoordinator = $slugCoordinator . "-" . $output;
                } else {
                    $slugCoordinator = $slugCoordinator;
                }
            }
        }

        $createPassword = new CreatePassword();
        $schoolAdminPassword = $createPassword->gucluSifreUret(15);
        $schoolAdminPasswordHash = password_hash($schoolAdminPassword, PASSWORD_DEFAULT);

        $createPassword2 = new CreatePassword();
        $schoolCoordinatorPassword = $createPassword2->gucluSifreUret(15);
        $schoolCoordinatorPasswordHash = password_hash($schoolCoordinatorPassword, PASSWORD_DEFAULT);

        $this->setSchool($slug, $this->old_slug, $this->name, $this->address, $this->district, $this->postcode, $this->city, $this->email, $this->telephone, $this->schoolAdminName, $this->schoolAdminSurname, $this->schoolAdminEmail, $this->schoolAdminTelephone, $this->schoolCoordinatorName, $this->schoolCoordinatorSurname, $this->schoolCoordinatorEmail, $this->schoolCoordinatorTelephone, $this->schId, $slugAdmin, $slugCoordinator, $schoolAdminPasswordHash, $schoolCoordinatorPasswordHash);

        if ((!empty($this->schoolAdminEmail) AND ($this->schoolAdminEmail != $this->old_admin_email)) OR (($this->schoolAdminName != $this->old_admin_name) OR ($this->schoolAdminSurname != $this->old_admin_surname))) {

            $mailer = new Mailer();
            $mailer->sendSchoolAdminEmail($this->schoolAdminName, $this->schoolAdminSurname, $this->schoolAdminEmail, $schoolAdminPassword, $slugAdmin, $this->name);
        }

        if ((!empty($this->schoolCoordinatorEmail) AND ($this->schoolCoordinatorEmail != $this->old_coord_email)) OR (($this->schoolCoordinatorName != $this->old_coord_name) OR ($this->schoolCoordinatorSurname != $this->old_coord_surname))) {

            $mailer = new Mailer();
            $mailer->sendSchoolCoordinatorEmail($this->schoolCoordinatorName, $this->schoolCoordinatorSurname, $this->schoolCoordinatorEmail, $schoolCoordinatorPassword, $slugCoordinator, $this->name);

        }

    }
}
