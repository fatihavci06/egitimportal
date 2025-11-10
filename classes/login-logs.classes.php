<?php

class LoginLogs extends Dbh
{

    protected function getLogsList()
    {

        $filtre_baslangic = isset($_GET['baslangic']) ? $_GET['baslangic'] : '';
        $filtre_bitis = isset($_GET['bitis']) ? $_GET['bitis'] : '';

        $sql = 'SELECT 
        li.deviceType, 
        li.deviceModel, 
        li.deviceOs, 
        li.browser,
        li.resolution, 
        li.ipAddress, 
        li.loginTime, 
        li.logoutTime, 
        u.name,
        u.surname,
        u.email
        FROM logininfo_lnp li
        INNER JOIN users_lnp u ON u.id = li.user_id';

        $whereClauses = [];
        $parameters = [];

        // Ders filtresi varsa ekle
        if (!empty($filtre_baslangic)) {
            $whereClauses[] = "li.loginTime >= ?";
            $parameters[] = $filtre_baslangic . ' 00:00:00';
        }

        // Sınıf filtresi varsa ekle
        if (!empty($filtre_bitis)) {
            $whereClauses[] = "li.loginTime <= ?";
            $parameters[] = $filtre_bitis . ' 23:59:59';
        }

        // WHERE koşulları varsa sorguya ekle
        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $sql .= " ORDER BY li.loginTime DESC";

        /* $stmt = $this->connect()->prepare('SELECT 
        li.deviceType, 
        li.deviceModel, 
        li.deviceOs, 
        li.browser,
        li.resolution, 
        li.ipAddress, 
        li.loginTime, 
        li.logoutTime, 
        u.name,
        u.surname
        FROM logininfo_lnp li
        INNER JOIN users_lnp u ON u.id = li.user_id ORDER BY li.loginTime DESC'); */

        $stmt = $this->connect()->prepare($sql);

        if (!$stmt->execute($parameters)) {
            $stmt = null;
            exit();
        }

        $loginInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $loginInfo;

        $stmt = null;
    }
}
