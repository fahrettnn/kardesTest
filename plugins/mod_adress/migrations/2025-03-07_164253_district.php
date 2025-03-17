<?php

namespace Migration;

defined('FCPATH') or die("Direct script access denied");

/**
 * District Migration class
 */
class District extends Migration
{
	public function up()
	{
		$this->addColumn('id int unsigned auto_increment');
		$this->addColumn('city_id varchar(11) null');
		$this->addColumn('district_name varchar(60) null');
		$this->addColumn('status enum("1","2")  default "1"');
		$this->addColumn('date_created datetime default current_timestamp');
		$this->addColumn('date_updated datetime default null');

		$this->addPrimaryKey('id');
		$this->addKey('city_id');
		$this->addKey('district_name');
		$this->addKey('date_created');

		$this->createTable('tbl_district');
	}

	public function down() { $this->dropTable('tbl_district'); }
}