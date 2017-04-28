<?php

require_once __DIR__.'/../vendor/autoload.php';

/**
 * THIS IS A TOTATLLY SPECIFIC SCRIPT FOR ONE OF MY CUSTOMERS (Clube dos Oficiais - clubedosoficiais.org.br)
 */

define('DELIMITER', ';');
define('ENCLOSURE', '"');
// fields on csv (as on Excel)
define('DIA', 0);
define('MES', 1);
define('PATENTE', 2);
define('NOME', 3);
define('EMAIL', 4);
define('SEXO', 5);
define('NASCIMENTO', 6);
define('CLASSE', 7);

$input = $argv[1];
$tempCsv = __DIR__.'/temp.csv';
$output = $argv[2];

try {
	$csvConverter = new \ExcelUtils\Excel2Csv();
	$csvConverter->convert($input, $tempCsv);	
} catch (\Exception $e) {
	die("\n>> Error on converting: ".$e->getMessage()."\n");
}

// read the generated csv
$fp = fopen($tempCsv, 'r');
if (!$fp) {
	die("\n>> Error trying to read file $output\n");
}

$fpw = fopen($output, 'w');
if (!$fpw) {
	die("\n>> Error trying to open file for export formatted CSV.\n");
}

try {
	$newRows = [];
	$firstRow = fgetcsv($fp, 0, DELIMITER, ENCLOSURE); // not used (field names)
	while ($row = fgetcsv($fp, 0, DELIMITER, ENCLOSURE)) {
		$sexo = 'M';
		switch (trim($row[SEXO])) {
			case 'o': case 'O': $sexo = 'M'; break;
			case 'a': case 'A': $sexo = 'F'; break;
		}
		$nascimento = preg_replace('/(\d{1,2})\/(\d{1,2})\/(\d{4})/i', '$3-$1-$2', trim($row[NASCIMENTO]));
		list($y, $m, $d) = explode('-', $nascimento);
		$nascimento = sprintf('%d-%02d-%02d', $y, $m, $d);
		$newRows[] = [
			trim(sprintf('%s %s', trim($row[PATENTE]), trim($row[NOME]))),
			trim($row[EMAIL]),
			$sexo,
			$nascimento
		];
	}
	foreach ($newRows as $row) {
		fputcsv($fpw, $row, DELIMITER, ENCLOSURE);
	}
} catch (\Exception $e) {
	die("\n>> Error on generating formatted CSV: ".$e->getMessage()."\n");
} finally {
	fclose($fp);
	fclose($fpw);
	unlink($tempCsv);
}