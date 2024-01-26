<?php
 


    // error_reporting(E_ERROR | E_PARSE);
    date_default_timezone_set("Asia/Jakarta");

    include("../../lib/common.php");
    //require 'vendor/autoload.php';

    if (isset($_POST["action"]) && $_POST["action"]=="logout") {
        // $sql = "UPDATE user_acc SET online_status_klas='0', last_session='".date("Y-m-d H:i:s")."' WHERE staff_id='".$_SESSION['username']."' ";
        // $db->query($sql);
        session_unset();
        session_destroy();
        echo("<script>window.location.href = '../../admin/login/'; </script>");
    }

    $title = explode("/", substr($_SERVER['REQUEST_URI'], 1))[0];
    $fn0 = basename($_SERVER["SCRIPT_FILENAME"]);
    $fn = ucfirst(basename($_SERVER["SCRIPT_FILENAME"], '.php'));
    if ($fn == "Index") {
        $fn = "Home";
    }
    $fn = ($_GET['e'] == 1) ? "Edit" : $fn;

    $check = explode("/", substr($_SERVER['REQUEST_URI'], 1))[1];
    $bypass = array('login','register','forgot_password');


	$AccessMenus = [];

    $sql = "
    SELECT bm.menu_link 
    FROM base_user_sys_menu bm 
    LEFT JOIN base_menu_link bml ON bml.menu_id = bm.menu_id
    WHERE bml.menu_id IS NOT NULL
    AND bml.role_id='" . addslashes($_SESSION['userrole']) . "'
    ORDER BY bm.menu_sequence ASC";

    $result = $db->query($sql);

    // Fetch all results
    $AccessMenus = $db->fetchAll($result);
    $AccessUser = array();

    foreach ($AccessMenus as $AccessMenu) {
        $AccessUser[] = explode("/", $AccessMenu['menu_link'])[0];
    }


    if ($_SESSION['is_login'] == 1) {
        $AccessUser[] = "home";
        $AccessUser[] = "account";
	
        if ($_SESSION['userrole'] != "900") {
            $AccessUser[] = "account";
            $AccessUser[] = "class_registration";
        }



        if ($check == "profile" && $fn == "Edit") {
        } elseif ($check == "student" && $fn == "View") {
        } elseif (!in_array($check, $AccessUser) && count($_SESSION) > 0) {
            echo("<script>alert('You dont have access for this module.'); </script>");
           echo("<script>window.location.href = '../../admin/home/'; </script>");
        } else {
        }
    }



    if (!in_array($check, $bypass) && count($_SESSION) == 0) {
         echo("<script>window.location.href = '../login/'; </script>");
        session_unset();
        session_destroy();
    } else if (!in_array($check, $bypass) && $_SESSION['is_login'] != 1){
     echo("<script>window.location.href = '../login/'; </script>");
        session_unset();
        session_destroy();
    }


    ?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Campus Website | <?=ucfirst($title)?> - <?=$fn?></title>

<!-- Global stylesheets -->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
<link href="../config/assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
<link href="../config/assets/css/all.min.css" rel="stylesheet" type="text/css">
<link href="../config/assets/css/colorbox.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../config/assets/css/custom.css" rel="stylesheet" type="text/css">
<!-- /global stylesheets -->


<!-- JS files -->
<script src="../config/assets/js/jquery.min.js"></script>
<script src="../config/assets/js/bootstrap.bundle.min.js"></script>
<script src="../config/assets/js/app.js"></script>
<script src="../config/assets/js/datatables.min.js"></script>
<script src="../config/assets/js/responsive.min.js"></script>
<script src="../config/assets/js/jquery.colorbox.js"></script>
<script src="../config/assets/js/custom.js"></script>
<!-- /JS files -->
  
<?php

    $useragent=$_SERVER['HTTP_USER_AGENT'];

    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
        $device = 1;
    } else {
        $device = 0;
    }

?>

<style>
			/* Example styles for visual clarity; please refine as needed */
			.card {
				border: 1px solid #ccc;
				padding: 20px;
				margin: 20px 0;
			}

			.card-header {
				display: flex;
				justify-content: space-between;
				align-items: center;
			}

			.badge {
				padding: 5px 10px;
				color: white;
			}

			table {
				width: 100%;
				border-collapse: collapse;
			}

			th, td {
				border: 1px solid #ccc;
				padding: 0px 10px 0px 10px;
				text-align: left;
			}

			.table-container {
				max-height: 500px; 
				overflow-y: auto;
				position: relative;
			}

			.table-container thead th {
				position: sticky;
				top: 0;
				z-index: 10;
				background-color: #f7f7f7;  /* light gray */
				font-weight: bold;  /* make the font bold */
				padding: 10px 10px;  /* vertical and horizontal padding */
				box-shadow: 0 2px 4px rgba(0,0,0,0.1);  /* subtle shadow for depth */
				border-radius: 5px;  /* round corners */
				transition: background-color 0.3s;  /* smooth transition for hover effect */
			}

			.table-container thead th:hover {
				background-color: #e5e5e5;  /* slightly darker gray on hover */
			}
			.table-container tbody tr:hover {
				background-color: #f0f0f0;  /* light gray background on hover */
			}
			#overlay {
				position: fixed; 
				display: none;
				width: 100%;
				height: 100%;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				background-color: rgba(0,0,0,0.5);
				z-index: 9999;  
				cursor: pointer;
			}

	</style>