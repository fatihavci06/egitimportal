<?php

class Permission extends Dbh
{


	function checkPageAccess($page, $sessionPackageId)
	{
		$db = new Permission();

		// page ile ilgili kayıt var mı?
		$stmt = $db->connect()->prepare("
        SELECT COUNT(*) 
        FROM page_permission pp
        INNER JOIN page_permission_package_id ppp ON pp.id = ppp.page_permission_id
        WHERE pp.page_url LIKE :page
    ");

		$stmt->execute([':page' => "%{$page}%"]);
		$count = (int)$stmt->fetchColumn();

		if ($count === 0) {
			// Kayıt yoksa sayfaya aynen devam
			return true;
		}

		// Kayıt var, o zaman sessiondaki package_id kontrolü
		if (!isset($sessionPackageId)) {

			// session package_id yoksa yetkisiz
			header("Location: upgrade-plan.php");
			exit;
		}

		// session package_id ile permission kontrol
		$stmt2 = $db->connect()->prepare("
        SELECT COUNT(*) 
        FROM page_permission pp
        INNER JOIN page_permission_package_id ppp ON pp.id = ppp.page_permission_id
        WHERE pp.page_url LIKE :page
        AND ppp.package_id = :package_id
    ");

		$stmt2->execute([
			':page' => "%{$page}%",
			':package_id' => $sessionPackageId
		]);

		$hasPermission = (int)$stmt2->fetchColumn();

		if ($hasPermission > 0) {
			return true; // Yetkili
		} else {
			// Yetkisiz, PHP header yerine JS ile yönlendir
			echo '<script type="text/javascript">
            window.location.href = "upgrade-plan.php";
          </script>';
			exit;
		}
	}
}
