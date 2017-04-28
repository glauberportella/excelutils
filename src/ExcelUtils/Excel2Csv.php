<?php

namespace ExcelUtils;

/**
 * Converts first sheet on spreadsheet file to CSV
 * 
 * @todo allow options: delimiter, enclosure, line ending, index of sheet to export
 */
final class Excel2Csv
{
	const DEFAULT_DELIMITER = ';';
	const DEFAULT_ENCLOSURE = '';
	const DEFAULT_LINE_ENDING = "\r\n";

	/**
	 * @param  string $inputFilepath  [description]
	 * @param  string $outputFilepath [description]
	 * @throws Exception
	 */
	public function convert($inputFilepath, $outputFilepath)
	{
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFilepath);
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
		$writer->setDelimiter(self::DEFAULT_DELIMITER);
		$writer->setEnclosure(self::DEFAULT_ENCLOSURE);
		$writer->setLineEnding(self::DEFAULT_LINE_ENDING);
		$writer->setSheetIndex(0); // only first
		$writer->save($outputFilepath);
	}
}