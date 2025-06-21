<?php

class User extends Dbh
{

    public function getUserById($id)
    {
        $stmt = $this->connect()->prepare('
            SELECT 
                users_lnp.*,
                schools_lnp.name AS schoolName,
                classes_lnp.id AS classId,
                classes_lnp.name AS className,
                classes_lnp.slug AS classSlug,
                lessons_lnp.id AS lessonsId,
                lessons_lnp.name AS lessonName,
                lessons_lnp.slug AS lessonSlug
            FROM users_lnp 
            LEFT JOIN schools_lnp ON users_lnp.school_id = schools_lnp.id
            LEFT JOIN classes_lnp ON users_lnp.class_id = classes_lnp.id
            LEFT JOIN lessons_lnp ON users_lnp.lesson_id = lessons_lnp.id
            WHERE users_lnp.id = ?
		');

        if (!$stmt->execute([$id])) {
            $stmt = null;
            exit();
        }

        $getData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $getData;
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE email = ?');

        if ($stmt->execute([$email])) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                return $user;
            } else {
                return null;
            }
        }

        return false;
    }


}