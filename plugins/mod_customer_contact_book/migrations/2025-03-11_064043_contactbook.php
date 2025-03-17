<?php

namespace Migration;

defined('FCPATH') or die("Direct script access denied");

/**
 * ContactBook Migration class
 */
class ContactBook extends Migration
{
	public function up()
	{
		$this->addColumn('contact_id int unsigned auto_increment');
		$this->addColumn('customer_id varchar(11) not null');
		$this->addColumn('contact_firstname varchar(150) not null');
		$this->addColumn('contact_lastname varchar(150) not null');
		$this->addColumn('contact_position varchar(100) null');
		$this->addColumn('contact_phone varchar(30) null');
		$this->addColumn('contact_email varchar(250) null');
		$this->addColumn('contact_gsm_number varchar(30) null');
		$this->addColumn('added_by_user text not null');
		$this->addColumn('date_created datetime default current_timestamp');
		$this->addColumn('date_updated datetime default null');

		$this->addPrimaryKey('contact_id');
		$this->addKey('date_created');
		$this->createTable('tbl_contactbook');
	}

	public function down()
	{
		$this->dropTable('tbl_contactbook');
	}
}