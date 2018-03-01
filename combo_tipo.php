<?php 
include_once("MySql.class.php");
include_once("ComboBox.class.php");


					$oCombo = new ComboBox("cmbTipoSolicitud");
					$oCombo->SetTableName("tb_catalogo");
					$oCombo->SetIndexField("IdCatalogo");
					$oCombo->SetTextField("Descripcion");
					$oCombo->SetConditions("Estado='A'");
					//$oCombo->SetWidth(285);
					$oCombo->SetClassName("form-control");
					$oCombo->SetFirstOptionBlank(true,"-- Escoja Tipo --");
					$oCombo->SetOrderBy("Descripcion");
					$oCombo->SetUTF8();
					$oCombo->Show();

?>
