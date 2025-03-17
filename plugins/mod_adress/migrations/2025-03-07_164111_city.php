<?php

namespace Migration;

defined('FCPATH') or die("Direct script access denied");

/**
 * City Migration class
 */
class City extends Migration
{
	public function up()
	{
		$this->addColumn('id int unsigned auto_increment');
		$this->addColumn('city_name varchar(255) null');
		$this->addColumn('status enum("1","2")  default "1"');
		$this->addColumn('date_created datetime default current_timestamp');
		$this->addColumn('date_updated datetime default null');

		$this->addPrimaryKey('id');
		$this->addKey('city_name');
		$this->addKey('date_created');

		$this->createTable('tbl_city');
	}

	public function down(){ $this->dropTable('tbl_city'); }
}