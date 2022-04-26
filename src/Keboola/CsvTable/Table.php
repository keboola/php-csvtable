<?php

declare(strict_types=1);

namespace Keboola\CsvTable;

use Keboola\Csv\CsvOptions;
use Keboola\Csv\CsvWriter;
use Keboola\Temp\Temp;
use UnexpectedValueException;

/**
 * CsvFile class with attribute, primaryKey, incremental and name properties
 */
class Table extends CsvWriter
{
    protected array $attributes = [];

    /**
     * @var string[]
     */
    protected array $primaryKey;

    protected Temp $temp;

    protected string $name;

    protected ?bool $incremental = null;

    /**
     * @var array
     */
    protected array $header = [];

    public function __construct(
        string $name,
        array $header = [],
        bool $writeHeader = true,
        ?Temp $temp = null,
        string $delimiter = CsvOptions::DEFAULT_DELIMITER,
        string $enclosure = CsvOptions::DEFAULT_ENCLOSURE,
        string $lineBreak = "\n"
    ) {
        $this->name = $name;
        $this->temp = $temp ?: new Temp('csv-table');
        $this->header = $header;
        $tmpFile = $this->temp ->createTmpFile($name);
        parent::__construct($tmpFile->getPathname(), $delimiter, $enclosure, $lineBreak);

        if (!empty($this->header) && $writeHeader) {
            $this->writeRow($this->header);
        }
    }

    public function __destruct()
    {
        parent::__destruct();
        $this->temp->remove();
    }

    public function getPathName(): string
    {
        return $this->fileName;
    }

    public function getHeader(): array
    {
        return $this->header;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function addAttributes(array $attributes): void
    {
        $this->attributes = array_replace($this->attributes, $attributes);
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setIncremental(bool $incremental): void
    {
        $this->incremental = $incremental;
    }

    public function isIncrementalSet(): bool
    {
        return $this->incremental !== null;
    }

    public function getIncremental(): bool
    {
        if ($this->incremental === null) {
            throw new UnexpectedValueException('Incremental is not set.');
        }

        return $this->incremental;
    }

    /**
     * @brief Set a primaryKey (to combine multiple columns, use array or comma separated col names)
     * @param string|array $primaryKey
     */
    public function setPrimaryKey($primaryKey): void
    {
        if (!is_array($primaryKey)) {
            $primaryKey = explode(',', $primaryKey);
        }

        $this->primaryKey = $primaryKey;
    }

    /**
     * @return string|array
     */
    public function getPrimaryKey(bool $asArray = false)
    {
        return empty($this->primaryKey)
            ? null
            : (
                $asArray
                ? $this->primaryKey
                : implode(',', $this->primaryKey)
            );
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
