<?php

class SuspiciousLogs extends Dbh
{

    protected function getSuspiciousLogsList()
    {
        $stmt = $this->connect()->prepare('SELECT 
        deviceType, 
        deviceModel, 
        deviceOs, 
        browser,
        resolution, 
        ipAddress, 
        attempt_time FROM suspicious_attempts_lnp ORDER BY attempt_time DESC');

        if (!$stmt->execute()) {
            $stmt = null;
            exit();
        }

        $suspiciousAttemptData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $suspiciousAttemptData;

        $stmt = null;
    }
}
