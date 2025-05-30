<?php

class getSupportSubject extends Dbh
{
    public function getSubject()
    {
        $stmt = $this->connect()->prepare('SELECT name, id FROM support_center_subjects_lnp');

        if (!$stmt->execute()) {
            $stmt = null;
            exit();
        }

        $subject =  $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $subject;
    }
}
