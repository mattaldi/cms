<!DOCTYPE html>
<html lang="en">
<head>
<?php include('../main_resource.php'); ?>
<?php

$sql 				= "SELECT code from program where HoD='".$_SESSION['username']."' ";
$datas 				= $db->query($sql)->fetchArray();
$user_program_code 	= $datas['code'];	


if (isset($_POST["action"]) && $_POST["action"]=="add_subject") {
    $program_code = $user_program_code;
    $day = $_POST['day'];
    $time_start = $_POST['time_start'];
    $time_end = $_POST['time_end'];
    $time1 = strtotime($_POST['time_start']);
    $time2 = strtotime($_POST['time_end']);
    $duration = round(abs($time2 - $time1) / 3600, 2);


    $learn_mode = 'ft';
    $Semid = $_POST['xsemester_id'];
    $Subj = $_POST['subject_code'];
    $xsubject_code = $Subj;
    /*********************** generate class id start*********************/
    $sql="SELECT max(right(classid,3)) as num FROM subject_class where classid like '%$Semid%' and semester_id='$Semid' 
			and subject_code='$Subj'";
    $rs=$db->query($sql)->fetchArray();




    $seq_number=$rs["num"]+1;
    $str_len=strlen($seq_number);

    if ($str_len==1 || $str_len==null) {
        $class_id='00'.$seq_number;
        $classid=$xsubject_code.$Semid.$class_id;
    } elseif ($str_len==2) {
        $class_id='0'.$seq_number;
        $classid=$xsubject_code.$Semid.$class_id;
    } elseif ($str_len==3) {
        $class_id=$seq_number;
        $classid=$xsubject_code.$Semid.$class_id;
    } elseif ($str_len==4) {
        $class_id=$seq_number;
        $classid=$xsubject_code.$Semid.$class_id;
    } elseif ($str_len==5) {
        ?>
				<script language="javascript">
				alert("You have reach maximum Class ID. Please contact your vendor!!!");
				</script>
			<?php
    } else {
    }
    //generate class id end



    $xsemester_id = $_POST['xsemester_id'];

    $usrid = $_SESSION['username'];
    $ref = $_POST['ref'];
	
	/*
    if($ref == ""){
        $ref = $_POST['classid'];
    }
	*/	
	
	
    $xempid = $_POST['xempid'];
    $class_mode = $_POST['class_mode'];
    $room_id 	= $_POST['room_id'];
    $time_start = $_POST['time_start'];
    $time_end 	= $_POST['time_end'];
    $day 	= $_POST['day'];
	$xclass_max = 40;

    $curdatetime = date("Y-m-d H:i:s");
	
	
	 $clash_status = checkClash_edit($_POST['classid'], $program_code,'202303', $day, $time_start, $time_end, $room_id);

	if($clash_status == 1 && $room_id !='online'){
		    echo('<script> alert("The schedule you choose is clashed. Please choose other schedule."); </script>');
	} else {	
	
		$sql="
		UPDATE subject_class 
		SET 
		room_id = '".$_POST['room_id']."',
		time_start = '".$_POST['time_start']."',
		time_end = '".$_POST['time_end']."',
		day = '".$_POST['day']."',
		class_mode = '".$_POST['class_mode']."',
		empid = '".$_POST['xempid']."',
		ref_subject = '".$ref."'
		WHERE classid='".$_POST['classid']."'
	";




    $db->query($sql);


	}
 
    echo("<script>window.location.href = 'index.php?session_id=".$_POST['xsemester_id']."'; </script>");
















    /*
    $sql = "INSERT INTO ps_pathway SET subject_code='".$_POST['subject_code']."',
                                        plan_sem='".$_POST['plan_sem']."',
                                        sem_type='FS".$_POST['plan_sem']."',
                                        program_code='".$_POST['program_code']."',
                                        ps_subj_type='".$_POST['ps_subj_type']."',
                                        ps_id='".$_POST['ps_id']."',
                                        created_date='".$curdatetime."',
                                        created_by='".$_SESSION['username']."'
                                        ";
*/
    //	$db->query($sql);


    //echo("<script>window.location.href = 'view.php?id=".$_POST['ps_id']."'; </script>");
}

?>
</head>

<body>


	<?php include('../main_navbar.php'); ?>

	<!-- Page content -->
	<div class="page-content">

		<!-- Main sidebar -->
		<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">

			<!-- Sidebar content -->
			<div class="sidebar-content">


                <?php include('../user_menu.php');?>
                <?php include('../main_navigation.php');?>

			</div>
			<!-- /sidebar content -->
			
		</div>
		<!-- /main sidebar -->


		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Inner content -->
			<div class="content-inner">

			<?php //include('../page_header.php');?>

				<!-- Content area -->
				<div class="content">

					<!-- Main charts -->
					<div class="row">
						<div class="col-md-12">
 
							<!-- Basic datatable -->
							<div class="card">
								<div class="card-header header-elements-inline">
									<h6 class="card-title"> Edit Class</h6>
									<div class="header-elements">
									<a href="index.php" class="badge ml-2" style="background: #2980b9; color: #fff">Back</a>
									</div>
								</div>
                                <?php
 
									$sql = "
										SELECT sc.semester_id, sc.classid, 
										sc.subject_code, sbj.subject_eng,
										ne.empid, ne.name,
										sc.class_mode,
										sc.ref_subject,
										sc.time_start,
										sc.time_end,
										sc.room_id,
										sc.day												
										FROM subject_class sc
										LEFT JOIN `subject` sbj ON sbj.subject_code = sc.subject_code  
										LEFT JOIN `new_employee` ne ON ne.empid = sc.empid  
										WHERE sc.classid = '".addslashes($_GET['id'])."'
									";
									$data = $db->query($sql)->fetchArray();
								?>
								<div class="card-body">
 				                	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
									 <input type="hidden" name="id" value="<?=$_GET['id']?>">                                     
									 <input type="hidden" name="e" value="<?=$_GET['e']?>">                                     
									 <input type="hidden" name="action" value="add_subject">         
									 <input type="hidden" name="classid" value="<?=$_GET['id']?>">                                     
									 

									<div class="row">

									<div class="col-md-2">
 
										<div class="form-group">
											<label>Session:</label> 
                                            <select name="xsemester_id" class="form-control form-control-sm" onChange="get_reference_class(this.value);" required readonly>
                                                <?=dd_menu('semester', 'semester_id', 'semester_id', 'desc', $data['semester_id'], '-- Session --', '', '')?>
                                            </select>                                            
									 	</div>											
									</div>
										
							 
									<div class="col-md-2">
										<div class="form-group">
											<label>Class Mode:</label> 
											<select name="class_mode" class="form-control form-control-sm" required>
											<?=dd_menu('lookup_mode', 'class_mode', 'description', 'desc', $data['class_mode'],  '-- Class Mode--', 'WHERE status="1"', '')?>
											</select>                                            
										</div>										
									</div>
										
							 
									<div class="col-md-2">
										<div class="form-group">
											<label>Class ID:</label> 
											<input value="<?=$_GET['id'];?>" class="form-control form-control-sm" disabled>
 										</div>										
									</div>
										





									</div>
									
									<div class="row">

										<div class="col-md-4">
	
											<div class="form-group">
												<label>Subject:</label> 
												<input name="subject_code" id="subject_code" value="<?=$data['subject_code'];?>" class="form-control form-control-sm" placeholder="Select Subject" required readonly>
												<div id="subject_list"></div>
											</div>

										</div>

										<div class="col-md-4">
	
											<div class="form-group">
												<label>&nbsp;</label> 
												<input name="subject_name" id="subject_name" value="<?=$data['subject_eng'];?>" class="form-control form-control-sm" placeholder="Subject Name" required readonly>
											
											</div>

										</div>
																												
										</div>
 
									 

										<div class="row">

											<div class="col-md-4">
												<div class="form-group">
													<label>Lecturer:</label> 
													<input name="xempid" id="lecturer_id" value="<?=$data['empid'];?>" class="form-control form-control-sm" placeholder="Select Lecturer" required >
													<div id="lecturer_list"></div>
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
												<label>&nbsp;</label> 
												<input name="subject_name" id="lecturer_name" value="<?=$data['name'];?>"  class="form-control form-control-sm" placeholder="Lecturer Name" required readonly>
											</div>

										</div>
												
									</div>


	
									<div class="row">



                                    <div class="col-md-2">										
											
											<div class="form-group">
												<label>Room:</label> 
												<select name="room_id" class="form-control form-control-sm" required>
													<?=dd_day('room', 'id', 'description', 'asc', $data['room_id'],'-- Room --','','')?>
												</select>                                            
											</div>										
										
										</div>

										<div class="col-md-2">							
											
											<div class="form-group">
												<label>Day:</label> 
												<select name="day" class="form-control form-control-sm" required>
													<?=dd_day('lookup_day', 'id', 'title', 'asc', $data['day'],'-- Day --','','')?>
												</select>                                            
											</div>										
										
										</div>                                        
										<div class="col-md-1">
	
											<div class="form-group">
												<label>Time Start:</label>
												<select name="time_start" class="form-control form-control-sm" required>
													<?=dd_time('lookup_timer', 'syst_time', 'disp_time', 'asc', $data['time_start'],'- Time -','','')?>
												</select>    
											</div>
										
										</div>
										<div class="col-md-1">
											<div class="form-group">
												<label>Time End:</label>
												<select name="time_end" class="form-control form-control-sm" required>
													<?=dd_time('lookup_timer', 'syst_time', 'disp_time', 'asc', $data['time_end'],'- Time -','','')?>
												</select>    
											</div>
										</div>
									</div>
									
								 
									
									<div class="row">

										<div class="col-md-8">										
											


											<div class="form-group">
												<label>Reference Class ID:</label> <span style="font-size: 10px"><i>(leave blank if not applicable)</i></span>
												<select id="ref" name="ref" class="form-control form-control-sm"  >
													<option value=""> -- Select Reference Class ID -- </option>
												

<?php

$sql = "
        SELECT sc.classid, sbj.subject_eng, ne.name, cm.description AS description, (SELECT COUNT(*) FROM `subject_class` aa WHERE aa.ref_subject=sc.classid) AS dt 
        FROM subject_class sc
        LEFT JOIN `subject` sbj ON sbj.subject_code = sc.subject_code 
        LEFT JOIN `new_employee` ne ON ne.empid = sc.empid
        LEFT JOIN lookup_mode cm ON cm.class_mode = sc.class_mode
        WHERE sc.semester_id='".$data['semester_id']."'
		AND sc.classid !='".addslashes($_GET['id'])."'
        ORDER BY sbj.subject_eng
        ";
$result = $db->query($sql);
 

$rows_all = $result->fetchAll();


?>

<?php
foreach ($rows_all as $location) {
    ?>
    <option value="<?php echo $location["classid"]; ?>" <?php if($location["classid"] == $data['ref_subject']){ echo ("selected");}; ?> ><?=$location["subject_eng"];?> - <?=$location["classid"];?> - <?=$location["name"];?> - <?=$location["description"];?> - (<?=$location["dt"];?>)</option>
    <?php
}
?>















												</select>                                            
											</div>

										</div>
									 
									</div>
    







  
  
                                        <div class="text-right">
											<button type="submit" name="button"  class="badge ml-2" style="background: #2980b9; color: #fff">Submit</button>
										</div>
										 


									</form>
								</div>
							</div>
							<!-- /basic datatable -->


                        </div>
                    </div>






				</div>
				<!-- /content area -->

				<?php include('../main_footer.php');?>

			</div>
			<!-- /inner content -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->









    <script type="text/javascript">
    $(document).ready(function() {
        $('#subject_code').keyup(function() {
            var query = $(this).val();
            if (query != '') {
				console.log(query);
                $.ajax({
                    url: "add_search_subject.php?session_id=<?=$session_id?>&subject_code=<?=$filename?>",
                    method: "POST",
                    data: {
                        query: query
                    },
                    success: function(data) {
                        $('#subject_list').fadeIn();
                        $('#subject_list').html(data);
                    }
                });
            } else {
                $('#subject_list').html('');
            }
        });
       
        $('#lecturer_id').keyup(function() {
            var query = $(this).val();
            if (query != '') {
				console.log(query);
                $.ajax({
                    url: "add_search_lecturer.php?session_id=<?=$session_id?>&subject_code=<?=$filename?>",
                    method: "POST",
                    data: {
                        query: query
                    },
                    success: function(data) {
						console.log(data);
                        $('#lecturer_list').fadeIn();
                        $('#lecturer_list').html(data);
                    }
                });
            } else {
                $('#lecturer_list').html('');
            }
        });
       

    });
	
	
	function selectSubject(code,name){
		$("#subject_code").val(code);
		$("#subject_name").val(name);
		$('#subject_list').html('');
	}
	function selectLecturer(code,name){
		$("#lecturer_id").val(code);
		$("#lecturer_name").val(name);
		$('#lecturer_list').html('');
	}


	function get_reference_class(val) {
	
		$.ajax({
			type: "POST",
			url: "get_reference_class.php",
			data:'id='+val,
			beforeSend: function() {
				$("#semester_id").addClass("loader");
			},
			success: function(data){
				console.log(data);
				$("#ref").html(data);
				$("#ref").removeClass("loader");
			}
		});
	}
			

		
</script>
	





</body>
</html>
