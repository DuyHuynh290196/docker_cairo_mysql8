<?php
$pdo = new PDO("mysql:host=db;dbname=oxid", 'root', 'root', [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
]);

$outputFile = 'oxconfig_decrypted_export.sql';
$decodeKey = 'fq45QS09_fqyx09239QQ';

$query = $pdo->query("SELECT `OXID`, `OXSHOPID`, `OXMODULE`, `OXVARNAME`, `OXVARTYPE`, decode(`OXVARVALUE`, 'fq45QS09_fqyx09239QQ') as OXVARVALUE, `OXTIMESTAMP` FROM oxconfig");
$rows = $query->fetchAll(PDO::FETCH_ASSOC);

$fp = fopen($outputFile, 'w');
fwrite($fp, "-- Decrypted export of oxconfig table\n\n");

foreach ($rows as $row) {
    $sql = sprintf(
        "INSERT INTO `oxconfig` (`OXID`, `OXSHOPID`, `OXMODULE`, `OXVARNAME`, `OXVARTYPE`, `OXVARVALUE`, `OXTIMESTAMP`) VALUES\n" .
        "('%s', %d, '%s', '%s', '%s', '%s', '%s');\n\n",
        addslashes($row['OXID']),
        $row['OXSHOPID'],
        addslashes($row['OXMODULE']),
        addslashes($row['OXVARNAME']),
        addslashes($row['OXVARTYPE']),
        addslashes($row['OXVARVALUE']),
        $row['OXTIMESTAMP']
    );
    

    fwrite($fp, $sql);
}

fclose($fp);
echo "Export complete: $outputFile\n";


