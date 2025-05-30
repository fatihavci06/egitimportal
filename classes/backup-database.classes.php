<?php
class BackupDatabase extends Dbh
{

    protected $getMysqldumpPath;

    // public function __construct()
    // {
    //     parent::__construct();
    //     date_default_timezone_set('Europe/Istanbul');
    //     $this->getMysqldumpPath = $this->getMysqldumpPath();
    // }

    // protected function getMysqldumpPath()
    // {
    //     if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    //         $paths = [
    //             'C:\\xampp\\mysql\\bin\\mysqldump.exe',
    //         ];
    //     } else {
    //         $paths = [
    //             '/usr/bin/mysqldump',
    //             '/usr/local/bin/mysqldump',
    //             '/bin/mysqldump'
    //         ];
    //     }
    //     foreach ($paths as $path) {
    //         if (file_exists($path)) {
    //             return $path;
    //         }
    //     }
    // }

    protected function backupDatabase()
    {
        $tables = [];
        $sql = '';
        $db = $this->connect();

        $stmt = $db->query('SHOW TABLES');

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }

        foreach ($tables as $table) {
            $createTableResult = $db->query("SHOW CREATE TABLE `$table`");
            $createTableRow = $createTableResult->fetch(PDO::FETCH_NUM);
            $createTable = $createTableRow[1];

            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            $sql .= $createTable . ";\n\n";

            $rows = $db->query("SELECT * FROM `$table`");
            while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
                $values = array_map([$db, 'quote'], array_values($row));
                $sql .= "INSERT INTO `$table` VALUES(" . implode(", ", $values) . ");\n";
            }

            $sql .= "\n";
        }
        

        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="backup_"'.date('Y-m-d').'".sql"');
        echo $sql;
        exit;

        // $dir = __DIR__ . '/backups';
        // set_include_path(get_include_path() . PATH_SEPARATOR . $dir);
        // $filename = $name . '_' . date('Y-m-d_H-i-s') . '.sql';

        // header('Content-Type: application/sql');
        // header('Content-Disposition: attachment; filename="' . $filename . '"');
        // header('Pragma: no-cache');
        // header('Expires: 0');

        // $date = date('Y-m-d');

        // if (!file_exists($dir)):
        //     mkdir($dir, 0777, true);
        // endif;

        // $backup_dir = "{$dir}/backup_{$date}.sql";

        // exec("\"{$this->getMysqldumpPath}\" --user={$this->user} --password=\"{$this->pass}\" --host={$this->host} {$this->dbName} --result-file=\"{$backup_dir}\" 2>&1", $output, $return_var);

        // if ($return_var === 0) {
        //     echo json_encode(["status" => "success", "message" => 'Veritabanı yedekleme başarılı.']);
        // } else {
        //     echo json_encode(["status" => "error", "message" => 'Veritabanı yedeklenirken bir sorun oluştu!']);
        // }
    }
}
