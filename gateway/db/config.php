<?php 

    class DbConnection{
		private $host = "127.0.0.1:3307";
		private $user = "root";
		private $pass = "";
		private $dbname = "thetagateway";

		function connect(){
			$con = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
			return $con;
		}
	};

?>