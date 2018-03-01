<?php

class MySql{
	protected $conn;
  	private $host, $username, $password, $database, $query, $strSql;
  	private $errMessage;

  	function __construct($dbname = ""){

		if($dbname == ""){
			echo "No ha especificado una base de datos para la conexion.";
		}else{
			$this->host = "192.168.1.7";

			$this->username = "root";
			$this->password = "sa";

			$this->database = $dbname;
			$this->db_status = false;
			$this->errMessage = "";
			MySql::connect();
		}
  	}

	function connect(){
  		error_reporting(0);
		$this->conn = mysqli_connect($this->host, $this->username, $this->password);
		error_reporting(E_ERROR | E_WARNING | E_PARSE);

		if($this->conn == false){
			$this->errMessage = "No se pudo establecer conexi&oacute;n con <b>'".$this->host."'.</b>";
		}else{
			$this->db_status = mysqli_select_db($this->conn, $this->database);// or die("No se pudo establecer conexión con la base de datos <b>'".$this->database."'.</b>");

			if($this->db_status == false){
				$this->errMessage = "No se pudo establecer conexi&oacute;n con la base de datos <b>'".$this->database."'.</b>";
			}
		}
		/*
		mysql_query("SET character_set_client=utf8",$this->conn);
		mysql_query("SET character_set_connection=utf8",$this->conn);
		mysql_query("SET character_set_database=utf8",$this->conn);
		mysql_query("SET character_set_results=utf8",$this->conn);
		mysql_query("SET character_set_server=utf8",$this->conn);
		mysql_query("SET NAMES 'utf8'",$this->conn);
		*/
  	}

  	function execute($strSql){
		$this->strSql = $strSql;
		if(substr_count(strtolower($strSql),'insert') > 0 || substr_count(strtolower($strSql),'update') > 0 || substr_count(strtolower($strSql),'delete') > 0){
			mysqli_query($this->conn, $strSql ) or die("Error en la consulta: ".mysqli_error($this->conn)/*."<br>".$this->strSql*/);
		}elseif(substr_count(strtolower($strSql),'select') > 0){
			$this->query=mysqli_query($this->conn, $strSql ) or die("Error en la consulta: ".mysqli_error($this->conn)/*."<br>".$this->strSql*/);
		}else{
		}
	  }

	function get_num_rows(){
		return mysqli_num_rows($this->query); // or die("Error: ".mysql_error()." (".$this->strSql.")")
	}

	function get_num_fields(){
		return mysqli_num_fields($this->query); // or die("Error: ".mysql_error()." (".$this->strSql.")")
	}

  	function fetch_array(){
  		if($row=mysqli_fetch_array($this->query))
		  { return $row; }
		else
		  { return false; }
	  }

 	function get_rows(){
	  	$arrData = array();
		if(MySql::get_num_rows() > 0){
			$i=0;
			while($row=mysqli_fetch_array($this->query)){
				while(list($key, $value) = each($row)){
					$arrData[$i][$key] = trim($value);
				}
				$i++;
			}
		}
		return $arrData;
	}

	function get_last_insert_id(){
		return mysqli_insert_id($this->conn);
	}

	function BeginTransaction(){
		@mysql_query("BEGIN;", $this->conn);
	}

	function Commit(){
		@mysql_query("COMMIT;", $this->conn);
	}

	function RollBack(){
		@mysql_query("ROLLBACK;", $this->conn);
	}

	function getErrorMessage(){
		return $this->errMessage;
	}
}

?>