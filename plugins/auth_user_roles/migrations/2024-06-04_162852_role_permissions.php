<?php

namespace Migration;

defined('FCPATH') or die("Direct script access denied");

/**
 * Users Migration class
 */
class Role_permissions extends Migration
{

	public function up()
	{
		$this->addColumn('id int unsigned auto_increment');
		$this->addColumn('role_id varchar(11) not null default 0');
		$this->addColumn('permission varchar(120) null');
		$this->addColumn('disabled tinyint(1) unsigned default 0');

		$this->addPrimaryKey('id');
		$this->addKey('disabled');

		$this->createTable('tbl_role_permissions');

		/** to seed data: */
		$this->addData([
			'role_id'=>1,
			'permission'=>'all',
			'disabled'=>0,
		]);
		$this->insert('tbl_role_permissions');
	}

	public function down() { $this->dropTable('tbl_role_permissions'); }
}