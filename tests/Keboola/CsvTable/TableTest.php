<?php
namespace Keboola\Temp\Tests;

use Keboola\CsvTable\Table;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
	public function testCreate()
	{
		$table = new Table('filename', ['first_col', 'second_col']);
		$this->assertFileExists($table->getPathName());
		$this->assertEquals(
			'"first_col","second_col"
',
			file_get_contents($table->getPathName())
		);
		$this->assertEquals(
			array (
				0 => 'first_col',
				1 => 'second_col',
			),
			$table->getHeader()
		);
        $this->assertEquals('filename', $table->getName());
	}

    public function testDontWriteHeader()
    {
        $table = new Table('filename_suffix', ['first_col', 'second_col'], false);
        $this->assertFileExists($table->getPathName());
        $this->assertEquals('', file_get_contents($table->getPathName()));
        $this->assertEquals(
            array (
                0 => 'first_col',
                1 => 'second_col',
            ),
            $table->getHeader()
        );
    }

	public function testPrimaryKey()
	{
        $table = new Table('pk', ['id', 'user', 'data']);
        $table->setPrimaryKey('id,user');
        $this->assertEquals('id,user', $table->getPrimaryKey());
        $this->assertEquals(['id', 'user'], $table->getPrimaryKey(true));
	}

	public function testPrimaryKeyArray()
	{
        $table = new Table('pk', ['id', 'user', 'data']);
        $table->setPrimaryKey(['id', 'user']);
        $this->assertEquals('id,user', $table->getPrimaryKey());
        $this->assertEquals(['id', 'user'], $table->getPrimaryKey(true));
	}

	public function testPrimaryKeyEmpty()
	{
        $table = new Table('pk', ['id', 'user', 'data']);
        $this->assertNull($table->getPrimaryKey());
    }

    public function testIncremental()
    {
        $table = new Table('pk', ['id', 'user', 'data']);
        $this->assertFalse($table->getIncremental());

        $table->setIncremental(true);
        $this->assertTrue($table->getIncremental());

        $table->setIncremental(false);
        $this->assertFalse($table->getIncremental());
    }
}
