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
        $row['OXID'],
        $row['OXSHOPID'],
        $row['OXMODULE'],
        $row['OXVARNAME'],
        $row['OXVARTYPE'],
        $row['OXVARVALUE'],
        $row['OXTIMESTAMP']
    );

    fwrite($fp, $sql);
}

fclose($fp);
echo "Export complete: $outputFile\n";

// -------------------- Helper Functions --------------------

function decryptOxvarValue($hexValue, $type, $decodeKey) {
    if (strpos($hexValue, '0x') === 0) {
        $binary = hex2bin(substr($hexValue, 2));
    } else {
        $binary = $hexValue;
    }

    // 1. Try unserialize raw binary
    $unser = @unserialize($binary);
    if ($unser !== false || $binary === 'b:0;') {
        return formatByType($unser, $type);
    }

    // 2. XOR decrypt
    $xor = '';
    $keyLen = strlen($decodeKey);
    for ($i = 0; $i < strlen($binary); $i++) {
        $xor .= $binary[$i] ^ $decodeKey[$i % $keyLen];
    }

    // 3. Try unserialize XOR result
    $unserXor = @unserialize($xor);
    if ($unserXor !== false || $xor === 'b:0;') {
        return formatByType($unserXor, $type);
    }

    // 4. Try force unserialize if XOR looks like a serialized string
    if (preg_match('/^(s:\d+:"[^"]*";|a:\d+:{.*}|b:[01];)$/', $xor)) {
        $forced = @unserialize($xor);
        if ($forced !== false) {
            return formatByType($forced, $type);
        }
    }

    // 5. If XOR result is readable, return
    if (isMostlyPrintable($xor)) {
        return formatByType($xor, $type);
    }

    // 6. If raw binary is readable, return
    if (isMostlyPrintable($binary)) {
        return formatByType($binary, $type);
    }

    // Final fallback
    return '[undecodable: ' . bin2hex($binary) . ']';
}

function isMostlyPrintable($str) {
    return preg_match('/^[\x20-\x7E\r\n\t]+$/', $str);
}

function formatByType($value, $type) {
    // Clean up encoding for safe readable output
    $value = mb_convert_encoding($value, 'HTML-ENTITIES', 'UTF-8');

    switch ($type) {
        case 'bool':
            return $value ? '1' : '0';
        case 'str':
            return $value;
        case 'aarr':
        case 'arr':
            return serialize($value);
        default:
            return $value;
    }
}
