<?php
class Guardian
{
    private $pdo;

    public function __construct()
    {
        require_once 'dbh.classes.php';

        $this->pdo = (new dbh())->connect();
    }
    public function getOneGaurdian($teacher_id)
    {

        $stmt = $this->pdo->prepare('
            SELECT 
                users_lnp.*,
                schools_lnp.name AS schoolName,
                classes_lnp.id AS classId,
                classes_lnp.name AS className,
                classes_lnp.slug AS classSlug,
                lessons_lnp.id AS lessonsId,
                lessons_lnp.name AS lessonName,
                lessons_lnp.slug AS lessonSlug,
                child.email AS childEmail,
                child.telephone AS childPhone
             FROM users_lnp 
            LEFT JOIN schools_lnp ON users_lnp.school_id = schools_lnp.id
            LEFT JOIN classes_lnp ON users_lnp.class_id = classes_lnp.id
            LEFT JOIN lessons_lnp ON users_lnp.lesson_id = lessons_lnp.id
            LEFT JOIN users_lnp child ON users_lnp.child_id = child.id

            WHERE users_lnp.id = ?
		');

        if (!$stmt->execute([$teacher_id])) {
            $stmt = null;
            exit();
        }

        $getData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $getData;
    }
}