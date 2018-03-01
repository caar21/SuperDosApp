<?php 
include_once("MySql.class.php");

$oMysql = new MySql("compuser_dbucsg");

$strSql = " SELECT *
            FROM tb_usuario
            WHERE Usuario = '".$_POST["USUARIO"]."'
            AND Clave = '".$_POST["PASSWORD"]."'
        ";

$arrRecordset = array();
$oMysql->execute($strSql);

$RecourdCount = $oMysql->get_num_rows();

if($RecourdCount > 0){
    echo "OK";
}else{
    echo "Usuario o clave incorrecta";
} 



?>