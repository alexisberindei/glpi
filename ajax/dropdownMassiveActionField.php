<?php
/*
* @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2006 by the INDEPNET Development Team.
 
 http://indepnet.net/   http://glpi-project.org
 ----------------------------------------------------------------------

 LICENSE

	This file is part of GLPI.

    GLPI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    GLPI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GLPI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
*/

// ----------------------------------------------------------------------
// Original Author of file: Julien Dombre
// Purpose of file:
// ----------------------------------------------------------------------


include ("_relpos.php");
$AJAX_INCLUDE=1;
$NEEDED_ITEMS=array("search","contract","infocom","enterprise");
include ($phproot."/inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
header_nocache();

checkTypeRight($_POST["device_type"],"w");

if (isset($_POST["device_type"])&&isset($_POST["id_field"])&&$_POST["id_field"]){
	
	$search=$SEARCH_OPTION[$_POST["device_type"]][$_POST["id_field"]];	
	// Specific state case
	if ($_POST["id_field"]==31) $search["linkfield"]="state";
	// Specific budget case
	if ($_POST["id_field"]==50) $search["linkfield"]="budget";

	if (empty($search["linkfield"]))
		echo "<input type='hidden' name='field' value='".$search["field"]."'>";
	else 
		echo "<input type='hidden' name='field' value='".$search["linkfield"]."'>";
	if ($search["table"]==$LINK_ID_TABLE[$_POST["device_type"]]){ // field type
		if ($search["table"].".".$search["linkfield"]=="glpi_users.active"){
			dropdownYesNoInt("active",1);
		} else 
			autocompletionTextField($search["linkfield"],$search["table"],$search["field"]);
	} else { 
		
		if ($search["table"]=="glpi_infocoms"){ // infocoms case
			switch ($search["field"]){
				case "buy_date" :
				case "use_date" :
					showCalendarForm("massiveaction_form",$search["field"]);
					echo "&nbsp;&nbsp;";
					break;
				case "amort_type" :
					dropdownAmortType("amort_type");
					break;
				case "amort_time" :
					dropdownDuration("amort_time");
					break;
				case "warranty_duration" :
					dropdownContractTime("warranty_duration");
					echo " ".$lang["financial"][57]."&nbsp;&nbsp;";
					break;
				default :
					autocompletionTextField($search["field"],$search["table"],$search["field"]);
					break;
			}
		} else if ($search["table"]=="glpi_enterprises_infocoms"){ // Infocoms enterprises
			dropdown("glpi_enterprises","FK_enterprise");
		} else if ($search["table"]=="glpi_dropdown_budget"){ // Infocoms budget
			dropdown("glpi_dropdown_budget","budget");
		} else {// dropdown case
			dropdown($search["table"],$search["linkfield"]);
		}
	}
	echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$lang["buttons"][2]."\" >";
}

?>