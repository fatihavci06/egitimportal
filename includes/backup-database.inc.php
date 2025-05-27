<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Instantiate BackupDatabaseContr class
    include_once "../classes/dbh.classes.php";
    include_once "../classes/backup-database.classes.php";
    include_once "../classes/backup-database-contr.classes.php";

    $getBackup = new BackupDatabaseContr();
    $getBackup->backupDatabaseDb();
}
