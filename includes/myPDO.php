<?php 

class myPDO {
	private $hostname;
	private $dbname;
	private $username;
	private $password;

	public function connect() {
		$this->hostname = "localhost";
		$this->dbname = "lewliu";
		$this->username = "lewis8815";
		$this->password = "LewisIXY1314";
		try {
			$dsn = "mysql:host=".$this->hostname.";dbname=".$this->dbname;
			$pdo = new PDO($dsn,$this->username,$this->password);
			$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			return $pdo;
		} catch (Exception $e) {
			echo "Failed to Connect: " .$e->getMessage();
			die();
		}
	}
}

?>