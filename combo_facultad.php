<?php 
include_once("MySql.class.php");
include_once("ComboBox.class.php");


					$oCombo = new ComboBox("cmbFacultad");
					$oCombo->SetTableName("db_facultad");
					$oCombo->SetIndexField("IdFacultad");
					$oCombo->SetTextField("Descripcion");
					$oCombo->SetConditions("Estado='A'");
					//$oCombo->SetWidth(285);
					$oCombo->SetClassName("form-control");
					$oCombo->SetFirstOptionBlank(true,"-- Escoja Facultad --");
					$oCombo->SetOrderBy("Descripcion");
					$oCombo->SetUTF8();
					$oCombo->Show();

?>