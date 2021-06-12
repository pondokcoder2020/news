<?php

namespace PondokCoder;

class Connection {
	private static $conn;
	private static $allow_conn;
	private static $license_server;
	public function connect() {


		$params = parse_ini_file('database.ini');

		//PostgreSQL
		//==================================================================
		$conStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
			$params['host'],
			$params['port'],
			$params['database'],
			$params['user'],
			$params['password']);
		$pdo = new \PDO($conStr);



		//MySQL
		//==================================================================
		/*$conStr = sprintf("mysql:host=%s;dbname=%s",
			$params['host'],
			$params['database']);*/

		//$pdo = new \PDO($conStr, $params['user'], $params['password']);
		




		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		if(self::$allow_conn) {
            return $pdo;
        } else {
            return $pdo;
        }
	}
	public static function get() {
		if (null === static::$conn) {
			static::$conn = new static();
		}

		return static::$conn;
	}

	protected function __construct() {
		//Check Lisensi Client dan nomor harddisk
        //Check kunci koneksi
        self::$license_server = 'http://127.0.0.1/license';
	}

	private function __clone() {

	}

	private function __wakeup() {

	}

}
?>