<?php

namespace Migration;

defined('FCPATH') or die("Direct script access denied");

/**
 * Managers_roles class
 */
class User_roles extends Migration
{

	public function up()
	{
		$this->addColumn('id int unsigned auto_increment');
		$this->addColumn('role varchar(50) not null');
		$this->addColumn('disabled tinyint(1) unsigned default 0');

		$this->addPrimaryKey('id');
		$this->addKey('disabled');

		$this->createTable('tbl_user_roles');

		/** to seed data: */
		$this->addData([
			'role'	   => 'Admin',
			'disabled' => 0
		]);
		$this->insert('tbl_user_roles');
	}

	public function down() { $this->dropTable('tbl_user_roles'); }
}