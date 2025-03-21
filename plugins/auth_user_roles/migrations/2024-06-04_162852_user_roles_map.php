<?php

namespace Migration;

defined('FCPATH') or die("Direct script access denied");


/**
 * User_roles_map class
 */
class User_roles_map extends Migration
{
	public function up()
	{
		$this->addColumn('id int unsigned auto_increment');
		$this->addColumn('role_id varchar(11) default 0');
		$this->addColumn('user_id varchar(11) default 1');
		$this->addColumn('disabled tinyint(1) unsigned default 0');

		$this->addPrimaryKey('id');
		$this->addKey('disabled');

		$this->createTable('tbl_user_roles_map');

		/** to seed data: */
		$this->addData([
			'role_id'=>1,
			'user_id'=>1,
			'disabled'=>0,
		]);

		$this->insert('tbl_user_roles_map');
	}

	public function down() { $this->dropTable('tbl_user_roles_map'); }
}