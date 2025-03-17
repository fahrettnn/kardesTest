<?php
namespace App\Core\Database;
use \App\Core\Database\Connection;

defined('ROOT') or die("Direct script access denied");

class Model extends Connection
{
    protected static $db;
    public static $table;
    public static $select       = "*";
    public static $whereRawKey;
    public static $whereRawVal;
    public static $whereKey;
    public static $whereVal     = array();
    public static $orderBy      = NULL;
    public static $limit        = NULL;
    public static $offset       = NULL;
    public static $groupBy      = NULL;
    public static $having       = NULL;
    public static $join         = "";
    public static $leftjoin     = "";
    public static $rightjoin    = "";
    public static $FullJoin     = "";
    public static $CrossJoin    = "";

    public function __construct()
    {
        self::$db = new Connection();
    }
    
    public static function table($tableName)
    {
        $model = new self();
        self::$table        = $tableName;
        self::$select       = "*";
        self::$whereRawKey  = NULL;
        self::$whereRawVal  = NULL;
        self::$whereKey     = NULL;
        self::$whereVal     = NULL;
        self::$orderBy      = NULL;
        self::$limit        = NULL;
        self::$offset       = NULL;
        self::$groupBy      = NULL;
        self::$having       = NULL;
        self::$join         ="";
        self::$leftjoin     ="";
        self::$rightjoin    ="";
        self::$FullJoin     ="";
        self::$CrossJoin    ="";
        return $model;
    }

    public static function select($colums)
    {
        self::$select = (is_array($colums)) ? implode(",", $colums) : $colums;
        return new self();
    }

    public static function whereRaw($whereRaw, $whereRawVal)
    {
        self::$whereRawKey = "(" . $whereRaw . ")";
        self::$whereRawVal = $whereRawVal;
        return new self();
    }

    public static function where($colums1, $colums2 = NULL, $colums3 = NULL)
    {
        $model = new self();

        if (is_array($colums1) != false) {
            $keyList = [];
            foreach ($colums1 as $key => $value) {
                self::$whereVal[] = $value;
                $keyList[] = $key;
            }
            self::$whereKey = implode("=? AND ", $keyList) . "=?";
        } else if ($colums2 != NULL && $colums3 == NULL) {
            self::$whereVal[] = $colums2;
            self::$whereKey = $colums1 . "=?";
        } else if ($colums3 != NULL) {
            self::$whereVal[] = $colums3;
            self::$whereKey = $colums1 . $colums2 . "?";
        }
        return $model;
    }

    public static function orderBy($parameter)
    {
        self::$orderBy = $parameter[0] . " " . ((!empty($parameter[1])) ? $parameter[1] : "ASC");
        return new self();
    }

    public static function limit($start, $end = NULL)
    {
        self::$limit = $start . (($end != NULL) ? "," . $end : "");
        return new self();
    }

    public function offset($offset)
    {
        self::$offset = $offset;
        return new self();
    }

    public function groupBy($column)
    {
        self::$groupBy = $column;
        return new self();
    }

    public function having($condition)
    {
        self::$having = $condition;
        return new self();
    }

    public static function join($tableName, $thisColums, $joinColums)
    {
        self::$join .= "INNER JOIN " . $tableName . " ON " . self::$table . "." . $thisColums . "=" . $tableName . "." . $joinColums . " ";
        return new self();
    }

    public static function leftjoin($tableName, $thisColums, $joinColums)
    {
        self::$leftjoin .= "LEFT JOIN " . $tableName . " ON " . self::$table . "." . $thisColums . "=" . $tableName . "." . $joinColums . " ";
        return new self();
    }

    public static function rightjoin($tableName, $thisColums, $joinColums)
    {
        self::$rightjoin .= "RIGHT JOIN " . $tableName . " ON " . self::$table . "." . $thisColums . "=" . $tableName . "." . $joinColums . " ";
        return new self();
    }

    public static function FullJoin($tableName, $thisColums, $joinColums)
    {
        self::$FullJoin .= "FULL JOIN " . $tableName . " ON " . self::$table . "." . $thisColums . "=" . $tableName . "." . $joinColums . " ";
        return new self();
    }

    public static function CrossJoin($tableName)
    {
        self::$CrossJoin .= "CROSS JOIN " . $tableName;
        return new self();
    }

    public function get()
    {
        $SQL = "SELECT " . self::$select . " FROM " . self::$table . " ";
        $SQL .= (!empty(self::$join)) ? self::$join : "";
        $SQL .= (!empty(self::$leftjoin)) ? self::$leftjoin : "";
        $SQL .= (!empty(self::$rightjoin)) ? self::$rightjoin : "";
        $SQL .= (!empty(self::$FullJoin)) ? self::$FullJoin : "";
        $SQL .= (!empty(self::$CrossJoin)) ? self::$CrossJoin : "";

        if (!empty(self::$groupBy)) {
            $SQL .= "GROUP BY " . self::$groupBy . " ";
            if (!empty(self::$having)) {
                $SQL .= "HAVING " . self::$having . " ";
            }
        }

        $WHERE = NULL;
        if (!empty(self::$whereKey) && !empty(self::$whereRawKey)) {
            $SQL .= "WHERE " . self::$whereKey . " AND " . self::$whereRawKey . " ";
            $WHERE = array_merge(self::$whereVal, self::$whereRawVal);
        } else {
            if (!empty(self::$whereKey)) {
                $SQL .= "WHERE " . self::$whereKey . " ";
                $WHERE = self::$whereVal;
            }
            if (!empty(self::$whereRawKey)) {
                $SQL .= "WHERE " . self::$whereRawKey . " ";
                $WHERE = self::$whereRawVal;
            }
        }
        $SQL .= (!empty(self::$orderBy)) ? "ORDER BY " . self::$orderBy . " " : "";
        $SQL .= (!empty(self::$limit)) ? "LIMIT " . self::$limit : "";
        $SQL .= (!empty(self::$offset)) ? "OFFSET " . self::$offset : "";
        if ($WHERE != NULL)
            return self::$db->query($SQL, $WHERE);

        return self::$db->query($SQL);
    }

    public function first()
    {
        $rows = $this->get();
        return $rows ? $rows[0] : false;
    }

    public static function addCreate(array $data, $returnLastInsertId = false)
    {
        if(!empty($data))
        {
            $colums = array_keys($data);
            $columsVal = array_values($data);

            $pdo        = self::$db->connect();
            $SQL        = "INSERT INTO " . self::$table . " SET " . implode("=?, ", $colums) . "=?";
            $Addentity  = $pdo->prepare($SQL);
            $Sync       = $Addentity->execute($columsVal);

            if ($returnLastInsertId!=false) 
            {
                return $pdo->lastInsertId();
            }
            return $Sync ? true : false;
        }

        return false;
    }

    public function update(array $data)
    {
        if(!empty($data))
        {
            $colums = array_keys($data); /*$colums = array("title","description","category")*/
            $columsVal = array_values($data);
            $SQL = "UPDATE " . self::$table . " SET " . implode("=?, ", $colums) . "=? ";
            $WHERE = NULL;
            if (!empty(self::$whereKey) && !empty(self::$whereRawKey)) {
                $SQL .= "WHERE " . self::$whereKey . " AND " . self::$whereRawKey . " ";
                $WHERE = array_merge(self::$whereVal, self::$whereRawVal);
            } else {
                if (!empty(self::$whereKey)) {
                    $SQL .= "WHERE " . self::$whereKey . " ";
                    $WHERE = self::$whereVal;
                }
                if (!empty(self::$whereRawKey)) {
                    $SQL .= "WHERE " . self::$whereRawKey . " ";
                    $WHERE = self::$whereRawVal;
                }
            }

            if ($WHERE != NULL) {
                $data = array_merge($columsVal, $WHERE);
            }
            $updateentity = self::$db->connect()->prepare($SQL);
            $Sync = $updateentity->execute($data);

            return $Sync ? true : false;
        }

        return false;
    }

    public function delete()
    {
        $SQL = "DELETE FROM " . self::$table . " ";
        $WHERE = NULL;
        if (!empty(self::$whereKey) && !empty(self::$whereRawKey)) {
            $SQL .= "WHERE " . self::$whereKey . " AND " . self::$whereRawKey . " ";
            $WHERE = array_merge(self::$whereVal, self::$whereRawVal);
        } else {
            if (!empty(self::$whereKey)) {
                $SQL .= "WHERE " . self::$whereKey . " ";
                $WHERE = self::$whereVal;
            }

            if (!empty(self::$whereRawKey)) {
                $SQL .= "WHERE " . self::$whereRawKey . " ";
                $WHERE = self::$whereRawVal;
            }
        }
        $deleteentity = self::$db->connect()->prepare($SQL);
        $Sync = $deleteentity->execute($WHERE);

        return $Sync ? true : false;
    }
}