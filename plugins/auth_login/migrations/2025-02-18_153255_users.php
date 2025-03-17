<?php

namespace Migration;

use App\Core\Models\Security;

defined('FCPATH') or die("Direct script access denied");

/**
 * Users Migration class
 */
class Users extends Migration
{
	public function up()
	{
		$this->addColumn('user_id int unsigned auto_increment');
		$this->addColumn('user_firstname varchar(60) not null');
		$this->addColumn('user_lastname varchar(60) not null');
		$this->addColumn('user_email varchar(255) not null');
		$this->addColumn('user_password varchar(100) not null');
		$this->addColumn('user_phone varchar(30) null');
		$this->addColumn('id_number varchar(15) null');
		$this->addColumn('adress_id varchar(11) not null');
		$this->addColumn('user_img varchar(90) default "test.jpg"');
		$this->addColumn('user_type enum("0","1","2") default "1"');
		$this->addColumn('last_session datetime default current_timestamp');
		$this->addColumn('session_token varchar(255) null');
		$this->addColumn('status enum("0","1") default "1"');
		$this->addColumn('date_created datetime default current_timestamp');
		$this->addColumn('date_updated datetime default null');
		$this->addColumn('date_deleted datetime default null');

		$this->addPrimaryKey('user_id');
		$this->addKey('adress_id');
		$this->addKey('user_email');
		$this->addKey('session_token');
		$this->addKey('date_created');
		$this->addKey('date_updated');
		$this->addKey('date_deleted');
		
		$this->createTable('tbl_users');

		$this->addData([
			'user_firstname' => "Fahreddin",
			'user_lastname'  => "San",
			'user_email'  	=> "fahrettnn@gmail.com",
			'user_password'  => Security::securityPassword("12345678"),
			'user_phone'  	=> "+90 (545) 511 68 03",
			'user_type'		=> 0,
			"adress_id"		=> 1
		]);

		$this->insert('tbl_users');
	}

	public function down()
	{
		$this->dropTable('tbl_users');
	}
}