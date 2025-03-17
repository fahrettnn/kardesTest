<?php
namespace Migration;

use \App\Core\Database\Connection;

defined('FCPATH') or die("Direct script access denied");

class Migration extends Connection
{
	private $columns 		= [];
	private $keys 			= [];
	private $data 			= [];
	private $primaryKeys 	= [];
	private $foreignKeys 	= [];
	private $uniqueKeys 	= [];
	private $fullTextKeys 	= [];

	public function createTable(string $table)
	{
		if(!empty($this->columns))
		{

			$query = "CREATE TABLE IF NOT EXISTS $table (";

			$query .= implode(",", $this->columns) . ',';

			foreach ($this->primaryKeys as $key) {
				$query .= "primary key ($key),";
			}

			foreach ($this->keys as $key) {
				$query .= "key ($key),";
			}

			foreach ($this->uniqueKeys as $key) {
				$query .= "unique key ($key),";
			}

			foreach ($this->fullTextKeys as $key) {
				$query .= "fulltext key ($key),";
			}

			foreach ($this->foreignKeys as $key) {
				$query .= "foreign key $key,";
			}

			$query = trim($query,",");

			$query .= ")ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

			$this->query($query);
			if(!empty($this->error))
			{
				echo "\n\rError creating table $table! with error: ".$this->error;
				return;
			}

			$this->columns 		= [];
			$this->keys 		= [];
			$this->data 		= [];
			$this->primaryKeys 	= [];
			$this->foreignKeys 	= [];
			$this->uniqueKeys 	= [];
			$this->fullTextKeys = [];

			echo "\n\rTable $table created successfully!";
		}else{

			echo "\n\rColumn data not found! Could not create table: $table";
		}
	}

	public function insert(string $table)
	{
		if(!empty($this->data) && is_array($this->data))
		{

			foreach ($this->data as $row) {
				
				$keys = array_keys($row);
				$columns_string = implode(",", $keys);
				$values_string = ':'.implode(",:", $keys);

				$query = "INSERT INTO $table ($columns_string) VALUES ($values_string)";
				$this->query($query,$row);
			}

			$this->data = [];
			echo "\n\rData inserted successfully in table: $table";
		}else
		{
			echo "\n\rRow data not found! No data inserted in table: $table";
		}
	}

	public function addColumn(string $column)
	{
		$this->columns[] = $column;
	}

	public function addKey(string $key)
	{
		$this->keys[] = $key;
	}

	public function addPrimaryKey(string $primaryKey)
	{
		$this->primaryKeys[] = $primaryKey;
	}

	public function addUniqueKey(string $key)
	{
		$this->uniqueKeys[] = $key;
	}

	public function addFullTextKey(string $key)
	{
		$this->fullTextKeys[] = $key;
	}

	public function addForeignKey(string $column, string $referencedTable, string $referencedColumn)
	{
		$this->foreignKeys[] = "($column) REFERENCES $referencedTable($referencedColumn)";
	}

	public function addData(array $data)
	{
		$this->data[] = $data;
	}

	public function dropTable(string $table)
	{
		$query = "DROP TABLE IF EXISTS $table ";
		$this->query($query);

		echo "\n\rTable $table deleted successfully!";
	}
}
