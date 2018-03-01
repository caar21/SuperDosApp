<?php
include_once("MySql.class.php");

$oMysql = new MySql("compuser_dbucsg");

$strSql = " SELECT *
            FROM tb_solicitud
            WHERE Usuario = '".$_POST["hdnUsuario"]."'
        ";

$arrRecordset = array();
$oMysql->execute($strSql);

$RecourdCount = $oMysql->get_num_rows();

$strHtml = "";

if($RecourdCount > 0){
    $arrRecordSet = $objSql->get_rows();	
	
	$strHtml = "
	<thead>
 		<tr>
			<th data-column-id="id" data-type="numeric">Solicitud</th>
			<th data-column-id="sender">Asunto</th>
			<th data-column-id="sender">Facultad</th>
			<th data-column-id="received" data-order="desc">Fecha</th>
			<th data-column-id="link" data-formatter="link" data-sortable="false">Estado</th>
		</tr>
		
	</thead>
	<tbody>
	";
	
	for($i = 0; $i < $RecordCount; $i++){
		$strHtml .= "<tr>
						<td>".$arrRecordSet[$i]["IdSolicitud"]."</td>
						<td>".$arrRecordSet[$i]["IdTipo"]."</td>
						<td>".$arrRecordSet[$i]["IdFacultad"]."</td>
						<td>".$arrRecordSet[$i]["Fecha"]."</td>
						<td>".$arrRecordSet[$i]["Estado"]."</td>
					</tr>
			";
	}
	$strHtml .= "</tbody>";
	echo "OK|".$strHtml;
}else{
    echo "NO|-";
} 


?>