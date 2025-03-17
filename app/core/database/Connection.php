<?php
namespace App\Core\Database;

use \PDO;
use \PDOException;
use \App\Core\Helpers\ActionFilterHelper;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

defined('ROOT') or die("Direct script access denieds");

class Connection
{
    public static $query_id  = '';
    public $affected_rows    = 0;
    public $insert_id        = 0;
    public $error            = '';
    public $has_error        = false;
    public $table_exists_db  = '';
    public $missing_tables   = [];

    public function connect()
    {
        $DB_NAME        = $_ENV['DATABASE_NAME'];
        $DB_USER        = $_ENV['DATABASE_USERNAME'];
        $DB_PASSWORD    = $_ENV['DATABASE_PASSWORD'];
        $DB_HOST        = $_ENV['DATABASE_HOSTNAME'];
        $DB_DRIVER      = $_ENV['DATABASE_DRIVER'];

        $this->table_exists_db = $DB_NAME;

        $string = "$DB_DRIVER:hostname=$DB_HOST;dbname=$DB_NAME";
        try {
            $connection = new PDO($string,$DB_USER,$DB_PASSWORD);
            $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
            $connection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
            $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }catch (PDOException $e)
        {
            $log = new Logger('database');
            $log->pushHandler(new StreamHandler(realpath('.') . '/dev-tools/logs/database/error.log'));
            $log->error(
                'Veritabanı bağlantı hatası',
                [
                    "errorDateTime" => [date('Y-m-d H:i:s')],
                    'errorNumber' => $e->getCode(),
                    'errorMessage' => $e->getMessage(),
                    'errorFile' => $e->getFile(),
                    'errorLine' => $e->getLine(),
                ]
            );
            exit();
        }
        return $connection;
    }

    public function query(string $query, array $data = [], string $data_type = 'object')
    {
        $query = ActionFilterHelper::doFilter('before_query_query', $query);
        $data  = ActionFilterHelper::doFilter('before_query_data', $data);

        $this->error = '';
        $this->has_error = false;
        $con = $this->connect();

        try {

            $stm = $con->prepare($query);

			$result = $stm->execute($data);
			$this->affected_rows 	= $stm->rowCount();
			$this->insert_id 		= $con->lastInsertId();

            if ($result) {
                if ($data_type == 'object') {
                    $rows = $stm->fetchAll(PDO::FETCH_OBJ);
                } else {
                    $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
                }
            }

        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            $this->has_error = true;
        }

        $arr = [];
		$arr['query'] = $query;
		$arr['data'] = $data;
		$arr['result'] = $rows ?? [];
		$arr['query_id'] = self::$query_id;
		self::$query_id = '';

        $result = ActionFilterHelper::doFilter('after_query', $arr);
        if (is_array($result['result']) && count($result['result']) > 0) {
            return $result['result'];
        }
        return false;
    }

    public function table_exists(string|array $mytables): bool
    {
        global $APP;
        $this->missing_tables = [];

        if (empty($APP['tables'])) {
            $this->error = '';
            $this->has_error = false;

            $con = $this->connect();

            $query = "SELECT TABLE_NAME AS tables FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . $this->table_exists_db . "'";

            $res = $this->query($query);
            $result = $APP['tables'] = $res;
        } else {
            $result = $APP['tables'];
        }

        if ($result) {
            $all_tables = array_column($result, 'tables');

            if (is_string($mytables)) {
                $mytables = [$mytables];
            }

            $count = 0;
            foreach ($mytables as $key => $table) {
                if (in_array($table, $all_tables)) {
                    $count++;
                } else {
                    $this->missing_tables[] = $table;
                }
            }

            if ($count == count($mytables)) {
                return true;
            }
        }

        return false;
    }
}