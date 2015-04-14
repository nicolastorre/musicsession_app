<?php


class DBManager
{
	# @object, The PDO object
	private static $pdo;
	# @object, PDO statement object
	private $stmt;
	# @bool , Connected to the database
	private static $connected = false;
	# @array, The parameters of the SQL query
	// private $parameters;

	public static function getConnection() {
		$dbHost =  ConfigReader::get("dbHost");
		$dbName = ConfigReader::get("dbName");
		$charset = ConfigReader::get("charset");
		$user = ConfigReader::get("user");
		$pwd = ConfigReader::get("pwd");
		$infoserver = "mysql:host=".$dbHost.";dbname=".$dbName.";charset=".$charset;
		try
		{
			# Read settings from INI file, set UTF8
			self::$pdo = new PDO($infoserver,$user,$pwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			# We can now log any exceptions on Fatal error.
			self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			# Disable emulation of prepared statements, use REAL prepared statements instead.
			self::$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			# Connection succeeded, set the boolean to true.
			self::$connected = true;
		}
		catch (PDOException $e)
		{
			$error = $e->getMessage();
                        $e = new ErrorController("SQL error: ".$error);
                        $e->indexAction();
			die();
		}
	}

	public static function CloseConnection()
	{
		# Set the PDO object to null to close the connection
		# http://www.php.net/manual/en/pdo.connections.php
		self::$pdo = null;
	}

	private function init($sql, array $par)
	{
		# Connect to database
		if(!self::$connected) { self::getConnection(); }
		try {
			# Prepare query
			$this->stmt = self::$pdo->prepare($sql);
			# Execute SQL
			$this->success = $this->stmt->execute($par);
		}
		catch(PDOException $e)
		{
			$error = $e->getMessage()." ".$sql;
                        $e = new ErrorController("SQL error: ".$error);
                        $e->indexAction();
			die();
		}
	}

	public function query($sql, $par = array(), $fetchmode = PDO::FETCH_ASSOC)
	{
		$sql = trim($sql);
		$this->init($sql,$par);
		$rawStatement = explode(" ", $sql);
		# Which SQL statement is used
		$statement = strtolower($rawStatement[0]);
		if ($statement === 'select' || $statement === 'show') {
			return $this->stmt->fetchAll($fetchmode);
		}
		elseif ( $statement === 'insert' || $statement === 'update' || $statement === 'delete' ) {
			$this->stmt->closeCursor();
			return $this->stmt->rowCount();
		}
		else {
			return NULL;
		}
	}

	public function dblastInsertId() {
		// ajouter gestion de l'erreur
		return self::$pdo->lastInsertId();
	}
}

?>