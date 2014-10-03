<?php

namespace Keboola\CsvTable;

use Keboola\Csv\CsvFile;
use Keboola\Temp\Temp;

class Table extends CsvFile {
	/** @var array */
	protected $attributes = array();

	/** @var string */
	protected $primaryKey;

	/** @var Temp */
	protected $temp;

	/** @var string */
	protected $name;

	/**
	 * @brief Create a CSV file, and optionally set its header
	 *
	 * @param string $suffix File name Suffix
	 * @param array $header A header line to write into created file
	 * @param \Syrup\ComponentBundle\Filesystem\Temp $temp
	 * @return \Keboola\ExtractorBundle\Common\Table
	 */
	public static function create($name = '', array $header = [], Temp $temp = null)
	{
		$this->name = $name;

		if ($temp == null) {
			$temp = new Temp('csv-table');
		}

		$tmpFile = $temp->createTmpFile($suffix);
		$csvFile = new self($tmpFile->getPathname());
		// Write header
		if (!empty($header)) {
			$csvFile->writeRow($header);
		}

		// Preserve Temp to prevent deletion!
		$csvFile->setTemp($temp);

		return $csvFile;
	}

	/**
	 * @brief Resets attributes to key:value pairs from $attributes
	 * @param array $attributes
	 */
	public function setAttributes(array $attributes)
	{
		$this->attributes = $attributes;
	}

	/**
	 * @brief Adds attributes as key:value pairs from $attributes
	 * @param array $attributes
	 */
	public function addAttributes(array $attributes)
	{
		$this->attributes = array_replace($this->attributes, $attributes);
	}

	/**
	 * @brief Set a primaryKey (to combine multiple columns, use comma separated col names)
	 * @param string $primaryKey
	 */
	public function setPrimaryKey($primaryKey)
	{
		$this->primaryKey = $primaryKey;
	}

	public function getAttributes()
	{
		return $this->attributes;
	}

	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}

	public function setTemp(Temp $temp) {
		$this->temp = $temp;
	}

	public function getName()
	{
		return $this->name;
	}
}
