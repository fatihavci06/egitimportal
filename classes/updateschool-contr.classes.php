<?php

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

    public function __construct($old_slug, $name, $address, $district, $postcode, $city, $email, $email_old, $telephone)
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

        $this->setSchool($slug, $this->old_slug, $this->name, $this->address, $this->district, $this->postcode, $this->city, $this->email, $this->telephone);
    }
}
