<?php 
include_once("MySql.class.php");

$oMysql = new MySql("compuser_dbucsg");

$strSql = " SELECT Estudiante
            FROM db_estudiante
            WHERE Cedula = '".$_POST["USUARIO"]."'
            
        ";

$arrRecordset = array();
$oMysql->execute($strSql);

$RecourdCount = $oMysql->get_num_rows();

if($RecourdCount > 0){
    echo "OK";
}else{
    echo "Estudiante no existe";
} 



?>