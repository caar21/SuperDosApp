<?php
include_once("MySql.class.php");
class ComboBox
{
	private $Id = "";
	private $SelectedOption = 0;
	private $TableName = "";
	private $IndexField = "";
	private $TextField = "";
	private $OrderBy = "";
	private $Conditions = "1 = 1";
	private $ClassName = "";
	private $Width = 0;
	private $FirstOptionBlank = true;
	private $FirstOptionText = "";
	private $arrData = array();
	
	private $DisplayHtmlEntities = true;
	private $DisplayUTF8 = false;
	
	private $loadDataFromSource = false;
	private $loadDataFromArray = false;
	
	private $AjaxFunctionsCalls = "";
	private $AjaxJSFunctions = "";
	private $strStyle = "";
	private $enabled = true; 
	
	private $JSFunctionCall = "";
	private $JavaScript = "";
	private $oMySql;
	
	private $validators = array();
	/*
	Parámetros de validación:
	R = Requerido
	N = Numérico
	A = Alfabético
	AF = Alfanumérico
	M = Formato Mail
	...
	*/
	
	function __construct($Id = NULL){
		$this->Id = $Id;
		$this->oMySql = new MySql("compuser_dbucsg");
		$this->AjaxFunctionsCalls = "";
		$this->AjaxJSFunctions = "";
		$this->JSFunctionCall = "";
		$this->JavaScript = "";
	}

	function SetData($arr = NULL){
		$this->arrData = $arr;
		$this->loadDataFromArray = true;
	}

	function SetTableName($Value = NULL){
		$this->TableName = $Value;
		$this->loadDataFromSource = true;
	}
	
	function SetIndexField($Value = NULL){
		$this->IndexField = $Value;
	}
	
	function SetTextField($Value = NULL){
		$this->TextField = $Value;
	}
	
	function SetOrderBy($Value = NULL){
		if($Value != NULL && $Value != ""){
			$this->OrderBy = "ORDER BY ".$Value;
		}else{
			$this->OrderBy = $Value;
		}
	}
	
	function SetConditions($Value = "1 = 1"){
		$this->Conditions = $Value;
	}
	
	function SetSelectedOption($Value = NULL){
		$this->SelectedOption = trim($Value);
	}
	
	function SetClassName($Value = NULL){
		$this->ClassName = $Value;
	}
	
	function SetWidth($Value = NULL){
		$this->Width = $Value;
	}
	
	function SetStyle($Value = "")
	{
		$this->strStyle = $Value;
	}
	
	function SetEnabled($Value = true)
	{
		$this->enabled = $Value;
	}
	
	function SetMandatory(){
		$this->validators[] = "R";
	}
	
	function SetFirstOptionBlank($Value = true,$Text = ""){
		$this->FirstOptionBlank = $Value;
		$this->FirstOptionText = $Text;
	}
	
	function AddJavaScript($strParams){
		$arrParams = json_decode($strParams,true);
		if(array_key_exists("JSFunctionName",  $arrParams)){
			$FunctionName = $arrParams["JSFunctionName"];
			
		}else{
			$FunctionName = "";
		}
		
		if(array_key_exists("Script",  $arrParams)){
			$Script = $arrParams["Script"];
		}else{
			$Script = "";
		}
		
		if($FunctionName != ""/* && $Script != ""*/){
			$this->JSFunctionCall .= $FunctionName;
			
			$this->JavaScript .= "<script language=\"javascript\">
								  ".$Script."
								  </script>";
		}
	}
	
	
	function AddAjaxRequest($strParams){
		$arrParams = json_decode($strParams,true);
		
		if(array_key_exists("AjaxRequest",  $arrParams)){
			$strAjaxRequest = $arrParams["AjaxRequest"];
			
		}else{
			$strAjaxRequest = "";
		}
		
		if(array_key_exists("SelectedValue",  $arrParams)){
			$strSelectedValue = $arrParams["SelectedValue"];
		}else{
			$strSelectedValue = "";
		}
		
		if(array_key_exists("Url",  $arrParams)){
			$strUrl = $arrParams["Url"];
			if(substr_count($strUrl,"?") > 0){
				$strUrl .= "&";
			}else{
				$strUrl .= "?";
			}
			
		}else{
			$strUrl = "";
		}
		
		if(array_key_exists("Target",  $arrParams)){
			$strTarget = $arrParams["Target"];
		}else{
			$strTarget = "";
		}
		
		$this->AjaxFunctionsCalls .= $strAjaxRequest."(\"".$strUrl."\",\"".$strTarget."\",".$strSelectedValue.");";
		
	}
	
	
	function SetHtmlEntities(){
		$this->DisplayHtmlEntities = true;
		$this->DisplayUTF8 = false;
	}
	
	
	function SetUTF8(){
		$this->DisplayHtmlEntities = false;
		$this->DisplayUTF8 = true;
	}
	
	
	function BuildComboBox()
	{
		$arrRecordSet = array();
		$RecordCount = 0;
		
		if($this->loadDataFromSource == true){
			if(trim($this->IndexField) != "" && trim($this->TextField) != "")
			{
				$strSql = "SELECT ".$this->IndexField.",
								  ".$this->TextField."	
						   FROM ".$this->TableName."
						   WHERE ".$this->Conditions."
						   ".$this->OrderBy;
				//echo $strSql;
				$this->oMySql->execute($strSql);
				$RecordCount = $this->oMySql->get_num_rows();		  
				if($RecordCount > 0){
					$arrRecordSet = $this->oMySql->get_rows();
					//print_r ($arrRecordSet);
				}
			}
		}
		
		if($this->loadDataFromArray == true){
			/*
			$tmpArrData = explode(",",str_replace("{","",str_replace("}","",$this->arrData)));
			
			for($i = 0; $i < count($tmpArrData); $i++){
				$arrOptionValue = explode("|",$tmpArrData[$i]);
				$arrRecordSet[$i][0] = trim($arrOptionValue[0]);
				$arrRecordSet[$i][1] = trim($arrOptionValue[1]);
			}*/
			$arrRecordSet = $this->arrData;
			$RecordCount = count($arrRecordSet);
		}
		
		
		$strValidators = "";
		$strValidatorsRestore = "";
		if(count($this->validators) > 0){
			$strValidators = " 	alt='".implode("|",$this->validators)."'";
			$strValidatorsRestore = " RestaurarCSS(this,\"TextBox-validation-error\",\"ComboBox\");";
		}
		

		$strJSCall = $this->AjaxFunctionsCalls.$this->JSFunctionCall;
		if($strJSCall != ""){
			$strJSCall = " onChange='javascript:".$strJSCall.$strValidatorsRestore."'";
		}else{
			$strJSCall = " onChange='javascript:".$strValidatorsRestore."'";
		}
		
		
		if($this->ClassName != ""){
			$strClass = " class='".$this->ClassName."'";
		}else{
			$strClass = "";
		}
		
		
		if($this->Width != NULL){
			$strWidth = " style='Width:".$this->Width."px;".$this->strStyle."'";
		}else{
			$strWidth = "";
		}
		
		
		$strEnabled = ($this->enabled == false)? " disabled" : "";
		
		
		
		if($this->AjaxFunctionsCalls != ""){
			$strComboBox = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
			
			$strComboBox .= "<tr><td><select name='".$this->Id."' id='".$this->Id."' ".$strClass." ".$strJSCall." ".$strWidth."".$strEnabled."".$strValidators.">";
			if($RecordCount > 0){
				if($this->FirstOptionBlank == true){
					$strComboBox .= "<option value='0' selected>".$this->FirstOptionText."</option>";
				}
				for($i = 0; $i < $RecordCount; $i++){
					$strSelected = ($arrRecordSet[$i][0] == $this->SelectedOption)? " selected" : "";
					if($this->DisplayHtmlEntities == true){
						$strComboBox .= "<option value='".$arrRecordSet[$i][0]."'".$strSelected.">".html_entity_decode($arrRecordSet[$i][1],ENT_QUOTES)."</option>";
					}else{
						$strComboBox .= "<option value='".$arrRecordSet[$i][0]."'".$strSelected.">".utf8_encode($arrRecordSet[$i][1])."</option>";
					}
					
				}
			}else{
				$strComboBox .= "<option value='0' selected></option>";
			}
			
			$strComboBox .= "</select></td>";
			$strComboBox .= "<td><div id=\"dvLoading".$this->Id."\" style=\"display:none\";><img src=\"images/loading.gif\" border=\"0\" align=\"left\" hspace=\"5\" /><font class=\"black_bold_text\">Cargando datos</font></div></td>";
			
			$strComboBox .= "</tr></table>".$this->JavaScript;
		}else{
			$strComboBox .= "<select name='".$this->Id."' id='".$this->Id."' ".$strClass." ".$strJSCall." ".$strWidth."".$strEnabled."".$strValidators.">";
			
			if($RecordCount > 0){
				if($this->FirstOptionBlank == true){
					$strComboBox .= "<option value='0' selected>".$this->FirstOptionText."</option>";
				}
				
				for($i = 0; $i < $RecordCount; $i++){
					$strSelected = ($arrRecordSet[$i][0] == $this->SelectedOption)? " selected" : "";
					if($this->DisplayHtmlEntities == true){
						$strComboBox .= "<option value='".$arrRecordSet[$i][0]."'".$strSelected.">".html_entity_decode($arrRecordSet[$i][1],ENT_QUOTES)."</option>";
					}else{
						$strComboBox .= "<option value='".$arrRecordSet[$i][0]."'".$strSelected.">".utf8_encode($arrRecordSet[$i][1])."</option>";
					}
				}
			}else{
				$strComboBox .= "<option value='0' selected></option>";
			}
			$strComboBox .= "</select>".$this->JavaScript;
		}
		
		
		return $strComboBox;	
	}
	
	function Show()
	{
		echo ComboBox::BuildComboBox();
	}
}
?>