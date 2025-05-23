<?php

class LoginLogs extends Dbh
{

    protected function getLogsList()
    {
        $stmt = $this->connect()->prepare('SELECT 
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
        INNER JOIN users_lnp u ON u.id = li.user_id');

        if (!$stmt->execute()) {
            $stmt = null;
            exit();
        }

        $loginInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $loginInfo;

        $stmt = null;
    }
}
