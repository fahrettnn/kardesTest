<?php

namespace Migration;

defined('FCPATH') or die("Direct script access denied");

/**
 * PasswordReset Migration class
 */
class PasswordReset extends Migration
{

	public function up()
	{
		$this->addColumn('pass_id int unsigned auto_increment');
		$this->addColumn('email varchar(255) not null');
		$this->addColumn('reset_code varchar(255) not null');
		$this->addColumn('expires_at datetime not null');
		$this->addColumn('date_created datetime default current_timestamp');
		$this->addColumn('used_at datetime null');
		$this->addColumn('ip_address varchar(45) not null');
		$this->addColumn('user_agent text not null');
		$this->addColumn('code_status enum("1","2","3") default 1');
		$this->addColumn('attempts tinyint(1) unsigned default 0');
		$this->addColumn('last_attempt_at datetime null');

		$this->addPrimaryKey('pass_id');
		$this->addKey('email');
		$this->addKey('reset_code');
		$this->addKey('expires_at');

		$this->createTable('tbl_password_reset');
	}

	public function down() { $this->dropTable('tbl_password_reset'); }
}