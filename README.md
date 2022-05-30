# Keboola CsvTable

## Description
A class that extends Keboola\CsvFile functionality by adding Keboola StorageApi Attribute and PrimaryKey variables

## Usage

```php
    use Keboola\CsvTable\Table;
	$table = new Table('name', ['id', 'column', 'names']);
    $table->writeRow(['1','row','data']);
    $table->addAttributes(['created_by' => $username]);
    $table->setPrimaryKey('id');
```

Result:

```csv
	id,column,names
	"1","row","data"
```

## Development

Clone this repository and init the workspace with following command:

```
git clone https://github.com/keboola/php-csvtable.git
cd php-csvtable
docker-compose build
docker-compose run --rm dev composer install --no-scripts
```

Run the test suite using this command:

```
docker-compose run --rm dev composer tests
```
## License

MIT licensed, see [LICENSE](./LICENSE) file.
