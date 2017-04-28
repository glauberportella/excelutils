<?php

require_once __DIR__.'/../vendor/autoload.php';

$argc = count($argv);

if ($argc < 3) {
	die("\nUse excel2csv.php <input_spreadsheet_filepath> <output_csv_filepath>\n");
}

echo "\nConverting ... ";

try {
	$converter = new \ExcelUtils\Excel2Csv();
	$converter->convert($argv[1], $argv[2]);
} catch (\Exception $e) {
	echo "\n>> An error occurred: ".$e->getMessage();
	echo "\nFailed.";
	exit(1);
}

echo " Success.\n";