<?php
 

include 'db.php';

$dbhost_home = 'localhost';
$dbuser_home = 'root';
$dbpass_home = 'itkj@1122sql'; 
$dbname_home = 'cms_v3';
 

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'itkj@1122sql';
$dbname = 'cms_v3';

$dbhost_klas2 = 'localhost';
$dbuser_klas2 = 'root';
$dbpass_klas2 = 'itkj@1122sql';
$dbname_klas2 = 'cms_v3';

$dbhome = new db($dbhost_home, $dbuser_home, $dbpass_home, $dbname_home);


$con = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
$conklas2 = mysqli_connect($dbhost_klas2, $dbuser_klas2, $dbpass_klas2,$dbname_klas2);

$db = new db($dbhost, $dbuser, $dbpass, $dbname);
$dbf = new db($dbhost, $dbuser, $dbpass, $dbname);

$dbklas2 = new db($dbhost_klas2, $dbuser_klas2, $dbpass_klas2, $dbname_klas2);
$dbfklas2 = new db($dbhost_klas2, $dbuser_klas2, $dbpass_klas2, $dbname_klas2);

$sql = "SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
$db->query($sql);

function getValue($tablename, $target_label, $source_label, $source_value){
    global $db;
    $sql = "SELECT $target_label FROM $tablename WHERE $source_label = '".addslashes($source_value)."'";
    $data = $db->query($sql)->fetchArray();
    return $data[$target_label];
}



function getDBFieldPost($postKey)
{
    $genFieldNm="";
    $varArrKey=explode("_",$postKey);

    foreach($varArrKey as $keyArr=>$valueArr)
    {
        if($keyArr>0)
            $genFieldNm.=(strlen($genFieldNm)>0?"_":"").$valueArr;
    }
    
    return $genFieldNm;
}

function getTableInd($postKey)
{
    $varArrKey=explode("_",$postKey);
    $genTableInd=substr($varArrKey[0],-2);
    return $genTableInd;
}

function genInsertSQL($tblNm,$arr)
{
    $field_list="";
    $value_list="";
    foreach($arr as $key=>$value)
    {

        if($key == "password"){
            $value = md5($value);
        }

        $field_list.=(strlen($field_list)>0?",":"").$key;
        $value_list.=(strlen($value_list)>0?",":"")."'".($value)."'";
    }
    
    $sqlGen="INSERT INTO ".$tblNm." ($field_list,created_date,created_by) 
            VALUES ($value_list,NOW(),'".$_SESSION["username"]."')";
    return $sqlGen;
}

function genUpdateSQL($tblNm,$arr,$label,$id)
{
    $field_list="";
    $value_list="";
    $update_list="";
    foreach($arr as $key=>$value)
    {

		$value = addslashes($value);
        if($key == "password"){
            if($value!=""){
                $value = md5($value);
            }
        }			

        if ($key == "password" && $value == "") {

        } elseif($key == "password" && $value != ""){
            $update_list.=(strlen($update_list)>0?",":"").$key."="."'".($value)."'";
        } else {
            $update_list.=(strlen($update_list)>0?",":"").$key."="."'".($value)."'";
        }
 			
    }
    
    $sqlGen = "UPDATE ".$tblNm." SET $update_list, modified_date=NOW(), modified_by='".$_SESSION["username"]."' WHERE $label='".$id."'";
    return $sqlGen;
}


function dd_menu0($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName.$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">-- Please Choose --</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	

function dd_menu($tblName, $value, $label, $sort, $selected, $label_desc, $filter)
{
    global $dbf;


    $orderBy = " ORDER BY ".$label." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." ".$filter." ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">".$label_desc."</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	
 
function dd_menu_klas2($tblName, $value, $label, $sort, $selected, $label_desc, $filter)
{
    global $dbklas2;


    $orderBy = " ORDER BY ".$label." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." ".$filter." ".$orderBy;
    
    $result = $dbklas2->query($sqldd_menu);
    
    echo "<option value=\"\">".$label_desc."</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	
 

function dd_time($tblName, $value, $label, $sort, $selected, $label_desc, $filter)
{
    global $dbf;


    $orderBy = " ORDER BY ".$value." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." ".$filter." ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">".$label_desc."</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	
 
function dd_menu_concentration($tblName, $value, $label, $sort, $program_code, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." WHERE program_code='".$program_code."'	".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">-- Please Choose --</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	



function dd_dept($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." WHERE status='ACTIVE' ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">-- Department --</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	

function dd_clearance_status($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY id ASC ";
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName."  ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">-- Clearance/SPC Status --</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	


function dd_month($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY id ASC";
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName.$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">-- Month --</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	


function dd_year($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY id ASC";
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." WHERE $value IN ('2022','2021') ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">-- Year --</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	

function dd_day($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY id ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName.$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">-- Please Choose --</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	

function dd_room($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY id ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." ORDER BY room_no";
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">-- Please Choose --</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	


function dd_menu_v2($tblName, $value, $label, $sort, $where,$selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." ".$where." ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">-- Please Choose --</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	

function dd_menu_v3($tblName, $value, $label, $sort, $where, $frontlabel,$selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." ".$where." ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">".$frontlabel."</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	



function dd_classid($tblName, $value, $label, $sort, $where,$session_id, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;
	
	if($session_id !=''){
		$rsess = " AND semester_id='".$session_id."' ";
	} else {
		$rsess = " AND semester_id='x'";
	}
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." ".$where.$rsess." ".$orderBy;
	
	$sqldd_menu = "
				SELECT sc.classid,  sc.classid, s.subject_eng
				FROM subject_class sc 
				LEFT JOIN `subject` s ON s.subject_code = sc.subject_code
				$where $rsess 
				GROUP BY sc.classid
				ORDER BY s.subject_eng
				
					";
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">-- Class ID --</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]." - ".$rows['subject_eng']." "."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]." - ".$rows['subject_eng']." "."</option>";
            }
        }
    }
}	



function dd_program($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." WHERE is_jgu=1 ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">-- Program --</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	

function dd_faculty($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." WHERE acad_display='YES' ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">-- Faculty --</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	

function dd_sem($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">Filter by Semester</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	

function dd_academic_sess($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." WHERE semester_id > 201600 ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">Academic Session</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	

function dd_menu_subject($tblName, $value, $label, $level, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;
   $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." WHERE length(subject_code) > 6 AND (SUBSTRING(subject_code, 4, 1)='".$level."' OR SUBSTRING(subject_code, 4, 1)='7') ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">Please Choose</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]." - ".$rows[$value]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]." - ".$rows[$value]."</option>";
            }
        }
    }
}	


function dd_menu_subject_plain($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." WHERE length(subject_code) > 6 ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">Please Choose</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]." - ".$rows[$value]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]." - ".$rows[$value]."</option>";
            }
        }
    }
}	

function dd_status($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName."  ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">-- Status --</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$value]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}


function dd_campus($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName."  ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">-- Campus --</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$value]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	
function dd_semester($tblName, $value, $label, $sort, $selected, $semester)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;
    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." WHERE semester_id >=".$semester." ".$orderBy;
    
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">Please Choose</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$value]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]."</option>";
            }
        }
    }
}	

function dd_subject_ps($tblName, $value, $label, $sort, $selected, $program_code)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;

    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." WHERE semester_id >=".$semester." ".$orderBy;
    
	$sqldd_menu = "SELECT MAX(ps.ps_id) AS ps_id FROM program_structure ps WHERE ps.program = '".$program_code."' AND ps.semester_id='202209'";
    $rs = $dbf->query($sqldd_menu);
	$dt = $rs->fetchArray();
	$ps_id = $dt['ps_id'];

	$sqldd_menu = "
			SELECT s.subject_code, s.subject_eng, pw.plan_sem 
			FROM ps_pathway pw
			LEFT JOIN `subject` s ON s.subject_code = pw.subject_code
			WHERE pw.ps_id = '".$ps_id."'
			ORDER BY pw.plan_sem, s.subject_eng
			";

	
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">Please Choose</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows['plan_sem'].". ".$rows[$label]." - ".$rows[$value]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows['plan_sem'].". ".$rows[$label]." - ".$rows[$value]."</option>";
            }
        }
    }
}	


function dd_subject_pathway($tblName, $value, $label, $sort, $selected, $program_code)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;

    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." WHERE semester_id >=".$semester." ".$orderBy;
    
	$sqldd_menu = "SELECT MIN(ps_id) AS ps_id FROM program_structure WHERE program = '".$program_code."' ";
    $rs = $dbf->query($sqldd_menu);
	$dt = $rs->fetchArray();
	$ps_id = $dt['ps_id'];

	$sqldd_menu = "
			SELECT s.subject_code, s.subject_eng, pw.plan_sem 
			FROM ps_pathway pw
			LEFT JOIN `subject` s ON s.subject_code = pw.subject_code
			WHERE pw.ps_id = 'x'
			ORDER BY pw.plan_sem, s.subject_eng
			";

	
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">Please Choose</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows['plan_sem'].". ".$rows[$label]." - ".$rows[$value]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows['plan_sem'].". ".$rows[$label]." - ".$rows[$value]."</option>";
            }
        }
    }
}	

function dd_subject_classid($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;

    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName."  ".$orderBy;
    	
	$sqldd_menu = "
			SELECT sc.classid, ne.name 
			FROM subject_class sc
			LEFT JOIN new_employee ne ON sc.empid = ne.empid
			WHERE sc.semester_id = '".$semester_id."'
			and sc.semester_id = 'x'
			";

	
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">Please Choose</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows[$label]." - ".$rows[$value]."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows[$label]." - ".$rows[$value]."</option>";
            }
        }
    }
}	

function dd_create_classid($tblName, $value, $label, $sort, $semester_id ,$selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;

    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." WHERE semester_id='".$semester_id."'  ".$orderBy;
    	
	$sqldd_menu = "
			SELECT sc.classid, ne.name, s.subject_code, s.subject_eng, pg.code, cc.description as class_cat 
			FROM subject_class sc
			LEFT JOIN subject s ON sc.subject_code = s.subject_code
			LEFT JOIN new_employee ne ON sc.empid = ne.empid
			LEFT JOIN program pg ON sc.created_by = pg.HoD
			LEFT JOIN lookup_class_category cc ON cc.code = sc.class_category
			WHERE sc.semester_id = '".$semester_id."'
			ORDER BY pg.code, sc.classid
			";

	
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">Please Choose</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".$rows['code']." - ".$rows['classid']." - ".$rows['subject_eng']." - ".$rows['name']." - ".$rows['class_cat']."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".$rows['code']." - ".$rows['classid']." - ".$rows['subject_eng']." - ".$rows['name']." - ".$rows['class_cat']."</option>";
            }
        }
    }
}	

function dd_lecturer($tblName, $value, $label, $sort, $selected)
{
    global $dbf;
    $orderBy = " ORDER BY ".$label." ".$sort;

    $sqldd_menu = "SELECT ".$value.",".$label." FROM ".$tblName." WHERE semester_id='202203'  ".$orderBy;
    	
	$sqldd_menu = "
			SELECT ne.empid, ne.name, ne.unit_id
			FROM new_employee ne
			WHERE xemployee_status = 'AC' AND unit_id IN ('FBMP','FPH','FECS')
			ORDER BY ne.unit_id, ne.name
			";

	
    $result = $dbf->query($sqldd_menu);
    
    echo "<option value=\"\">Please Choose</option>";
    if ($result->numRows() > 0) {
        $rows_all = $result->fetchAll();
        foreach ($rows_all as $rows) {
            $strselect="";
            if ($rows[$value] == $selected) {
                $strselect="selected";
                echo "<option ".$strselect." value='".$rows[$value]."'>".($rows['unit_id'])." - ".strtoupper($rows['name'])." - ".($rows['empid'])."</option>";
            } else {
                echo "<option value='".$rows[$value]."'>".($rows['unit_id'])." - ".strtoupper($rows['name'])." - ".($rows['empid'])."</option>";
            }
        }
    }
}	


function checkClash($program_code, $session_id, $day, $time_start, $time_end, $room_id)
{
	global $db;
	$checkSc = "SELECT count(*) as total_data 
				FROM subject_class 
				WHERE program_code = '".$program_code."' AND semester_id='$session_id' AND day = '$day' AND room_id = '$room_id' 
				  AND ((time_start <= '$time_start' AND time_end >= '$time_end') 
				OR (time_start >= '$time_start' && time_start < '$time_end') 
				OR (time_end > '$time_start' && time_end <= '$time_end'))";
 
	$result 	= $db->query($checkSc);
	$rows_all 	= $result->fetchArray();
	$total_data = $rows_all['total_data'];
 
	if($total_data > 0){
		return 1;
	} else {
		return 0;
	}
	 
}

function checkClash_edit($classid, $program_code, $session_id, $day, $time_start, $time_end, $room_id)
{
	global $db;
	 $checkSc = "SELECT count(*) as total_data 
				FROM subject_class 
				WHERE  
				classid !='".$classid."' 
				AND program_code = '".$program_code."' AND semester_id='$session_id' AND day = '$day' AND room_id = '$room_id' 
				  AND ((time_start <= '$time_start' AND time_end >= '$time_end') 
				OR (time_start >= '$time_start' && time_start < '$time_end') 
				OR (time_end > '$time_start' && time_end <= '$time_end'))";
 
	$result 	= $db->query($checkSc);
	$rows_all 	= $result->fetchArray();
	$total_data = $rows_all['total_data'];
 
	if($total_data > 0){
		return 1;
	} else {
		return 0;
	}
	 
}
