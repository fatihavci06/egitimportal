<?php

class User extends Dbh
{

    public function getUserById($id)
    {
        $stmt = $this->connect()->prepare('SELECT * FROM users_lnp WHERE id = ?');

        if ($stmt->execute([$id])) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                return $user;
            } else {
                return null;
            }
        }

        return false;
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