<?php

namespace Migration;

defined('FCPATH') or die("Direct script access denied");

class Customers extends Migration
{
	public function up()
	{
		$this->addColumn('customer_id int unsigned auto_increment');
		$this->addColumn('company_name varchar(255) not null');
		$this->addColumn('company_email varchar(255) null');
		$this->addColumn('company_phone varchar(20) null');
		$this->addColumn('company_fax varchar(25) null');
		$this->addColumn('company_web varchar(255) null');
		$this->addColumn('company_adres_id varchar(11) not null');
		$this->addColumn('company_status enum("0","1") default "1"');
		$this->addColumn('date_created datetime default current_timestamp');
		$this->addColumn('date_updated datetime default null');
		$this->addColumn('added_by_user_id varchar(11) not null');

		$this->addPrimaryKey('customer_id');
		$this->addKey('company_name');
		$this->addKey('company_email');
		$this->addKey('company_phone');
		$this->addKey('company_adres_id');
		$this->addKey('company_status');
		$this->addKey('added_by_user_id');
		$this->addKey('date_created');

		$this->createTable('tbl_customers');
	}

	public function down(){ $this->dropTable('tbl_customers'); }
}