<?php
include_once("MySql.class.php");

$strUsuario = $_POST["hdnUsuario"];
$strFacultad = $_POST["cmbFacultad"];
$strDirigido = $_POST["cmbDirigido"];
$strTipoSolicitud = $_POST["cmbTipoSolicitud"];
$strTexto = $_POST["txtSolicitud"];

$oMysql = new MySql("compuser_dbucsg");

$strSql = " INSERT tb_solicitud(
						Usuario,
						IdFacultad,
						IdTipo,
						Dirigo,
						Contenido,
						Estado,
						Fecha
					) VALUES (
						'".$strUsuario."',
						'".$strFacultad."',
						'".$strTipoSolicitud."',
						'".$strDirigido."',
						'".$strTexto."',
						'PEN',
						NOW()
					)

        ";

$oMysql->execute($strSql);
$strCodSolicitud = $oMysql-> get_last_insert_id();

echo "OK|Datos Guardados exitosamente. El código de su solicitud es #".$strCodSolicitud;


?>