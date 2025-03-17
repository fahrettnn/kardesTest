<?php

namespace Migration;

defined('FCPATH') or die("Direct script access denied");

/**
 * Adress Migration class
 */
class Adress extends Migration
{
	public function up()
	{
		$this->addColumn('id int unsigned auto_increment');
		$this->addColumn('city_id varchar(11) not null');
		$this->addColumn('discrit_id varchar(11) not null');
		$this->addColumn('adress_detail text not null');
		$this->addColumn('date_created datetime default current_timestamp');
		$this->addColumn('date_updated datetime default null');

		$this->addPrimaryKey('id');
		$this->addKey('city_id');
		$this->addKey('discrit_id');
		$this->addKey('adress_detail');
		$this->addKey('date_created');

		$this->createTable('tbl_adress');
	}

	public function down()
	{ $this->dropTable('tbl_adress'); }
}