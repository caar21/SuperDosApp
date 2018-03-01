<?php
include_once("MySql.class.php");

$oMysql = new MySql("compuser_dbucsg");

/*$strSql = " SELECT *
            FROM tb_solicitud 
            WHERE Usuario = '0918603101'        
        ";*/
$strSql ="	SELECT 
			s.IdSolicitud, 
			c.Descripcion Asunto,
			f.Descripcion Facultad,
			s.Fecha,
			(CASE s.Dirigo WHEN 0 THEN 'Decano(a) de la Facultad' ELSE 'Director(a) de la Facultad' END) DirigoA,
			(CASE s.Estado 
				WHEN 'PEN' THEN 'PENDIENTE'
				WHEN 'TRA' THEN 'EN TRAMITE'
				WHEN 'PRO' THEN 'PROCESADO'
			END) Estado
			FROM tb_solicitud s 
			join db_facultad f on s.IdFacultad=f.IdFacultad
			join tb_catalogo c on s.IdTipo=c.IdCatalogo

			WHERE s.Usuario='".$_POST["hdnUsuario"]."'
			
";
//'".$_POST["hdnUsuario"]."'
$arrRecordset = array();
$oMysql->execute($strSql);

$RecourdCount = $oMysql->get_num_rows();



//$arrRecordset = array();
//$oMysql->execute($strSql);

//$RecourdCount = $oMysql->get_num_rows();

$strHtml = "";
//echo $strSql;

if($RecourdCount > 0){
    
	$arrRecordSet = $oMysql->get_rows();	
	
	/*$strHtml = "<table id='grid-data' name='grid-data' class='table table-condensed table-hover table-striped table-print' >
	<thead>
 		<tr>
			<th data-column-id='Id' data-type='numeric'>Solicitud</th>
			<th data-column-id='Asunto'>Asunto</th>
			<th data-column-id='Facultad'>Facultad</th>
			<th data-column-id='Fecha' data-order='desc'>Fecha</th>
			<th data-column-id='Observaciones' data-order='desc'>Observaciones</th>
			<th data-column-id='Estado'>Estado</th>
		</tr>
		
	</thead>
	<tbody>
	";*/
	
	//<td>".$arrRecordSet[$i]["IdSolicitud"]."</td>
	for($i = 0; $i < $RecourdCount; $i++){
	
		$strHtml .= "<tr>
						<td>".$arrRecordSet[$i]["IdSolicitud"]."</td>
						<td>".$arrRecordSet[$i]["Asunto"]."</td>
						<td>".$arrRecordSet[$i]["Facultad"]."</td>
						<td>".$arrRecordSet[$i]["Fecha"]."</td>
						<td>".$arrRecordSet[$i]["Observaciones"]."</td>
						<td>".$arrRecordSet[$i]["Estado"]."</td>
					</tr>
			";
	}
	
	/*$strHtml .= "</tbody></table>";*/
	echo "OK|".$strHtml;
	}else{
		echo "NO|-".$strSql;
	}
//
?>