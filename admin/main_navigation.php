<!-- User menu -->
<div class="sidebar-section sidebar-user my-1">
    <div class="sidebar-section-body shadow-sm">
        <div class="media">
            <a href="#" class="mr-3">
 
            <?php
            
            // $app_year = substr($_SESSION['username'], 3,4);
            // $sql = "SELECT pic_contents, mime_type FROM new_employee_pic_$app_year WHERE empid='".$_SESSION['username']."'";							
            // $rs = $db->query($sql);
            // $dt = $rs->fetchArray();

            // if($dt['pic_contents']) {
            //     $imgType = empty($dt['mime_type']) ? 'png' : $dt['mime_type'];
                
            //     $imgnew = 'data:image/' . $imgType . ';base64,' . base64_encode($dt['pic_contents']);
            // } else {
            //     $imgnew = "../img/profile.png";  
            // }
            ?>

            <img src="<?= $imgnew ?>" class="rounded-circle" width="122" height="147">



			</a> 

            <div class="media-body">
                <div class="font-weight-semibold">
				
				<?=$_SESSION['username']?>
				
				</div>
                <div class="font-size-sm line-height-sm opacity-50">
				<?=$bio?>
                 
 
                </div>
            </div>

            <div class="ml-3 align-self-center">
                <button type="button" class="btn btn-outline-light-100 text-white border-transparent btn-icon rounded-pill btn-sm sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                    <i class="icon-transmission"  style="color: #263238; background: white"></i>
                </button>

                <button type="button" class="btn btn-outline-light-100 text-white border-transparent btn-icon rounded-pill btn-sm sidebar-mobile-main-toggle d-lg-none">
                    <i class="icon-cross2" style="color: #263238; background: white"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- /user menu -->
<!-- Main navigation -->
<div class="sidebar-section">
    <ul class="nav nav-sidebar" data-nav-type="accordion">

        <!-- Main -->
        <!--li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i></li-->
        <?php
        
            $sql = "
                    SELECT busm.menu_id AS id, parent_id, menu_module AS title, menu_link, menu_level AS `position`
                    FROM base_user_role bur
                    LEFT JOIN base_menu_link bml ON (bml.role_id=bur.role_id)
                    LEFT JOIN base_user_sys_menu busm ON (busm.menu_id=bml.menu_id)
                    WHERE bur.role_id='".$_SESSION['userrole']."' AND `menu_level` = '2'
                    ORDER BY busm.menu_sequence
                    ";


        
            $resultData = $db->query($sql);
            $menus = $db->fetchAll($resultData);
        ?>
        <?php foreach($menus as $menu){ ?>

            
<?php  //if($menu['id'] != 1){ ?> 

        <?php
            $display_submenu = 0;
			$nav_item_open = 0;
            $checkmenu = explode("/", substr($_SERVER['REQUEST_URI'], 1))[1];
            //nav-item-open
        ?>
            <?php if($menu['menu_link'] == "#"){ 

                            $sql = "
                            SELECT busm.menu_id AS id, parent_id, menu_module AS title, menu_link, menu_level AS `position`
                            FROM base_user_role bur
                            LEFT JOIN base_menu_link bml ON (bml.role_id=bur.role_id)
                            LEFT JOIN base_user_sys_menu busm ON (busm.menu_id=bml.menu_id)
                            WHERE bur.role_id='".$_SESSION['userrole']."' AND busm.parent_id='".$menu['id']."'
                            ORDER BY busm.menu_sequence
                            ";

                    $submenus = $db->query($sql)->fetchAll();
                    
					
                    foreach ($submenus as $submenu) {
                        if (strtolower(str_replace(" ","_",$submenu['title']))==$checkmenu) {
                            $nav_item_open = $nav_item_open + 1;
                        } else {
                            $nav_item_open = $nav_item_open + 0;
                        }
						
						
						
                    }
					
                }
             ?>
        
        <li class="nav-item <?php echo($nav_item_open == 1) ? "nav-item-open" : "" ?>  <?php echo($menu['menu_link']=="#") ? "nav-item-submenu" : "" ?>" >

			<?php if($menu['link']==""){?>		
				<a href="../../admin/<?=$menu['menu_link']?>" class="nav-link <?php echo(strtolower(str_replace(" ","_",$menu['title']))==$checkmenu) ? "active" : "" ?>">
			<?php } else { ?>
				<a target="_blank" href="../../admin/<?=$menu['link']?>" class="nav-link <?php echo(strtolower(str_replace(" ","_",$menu['title']))==$checkmenu) ? "active" : "" ?>">
			<?php } ?>


                <i class="<?=$menu['icon']?>"></i>
                <span>                                
                    <?=$menu['title']?> 
                </span>
            </a>
            <?php if($menu['menu_link'] == "#"){ ?>
            <?php 
            
                $sql = "
                SELECT busm.menu_id AS id, parent_id,menu_module AS title, menu_link, menu_level AS `position`
                FROM base_user_role bur
                LEFT JOIN base_menu_link bml ON (bml.role_id=bur.role_id)
                LEFT JOIN base_user_sys_menu busm ON (busm.menu_id=bml.menu_id)
                WHERE bur.role_id='".$_SESSION['userrole']."' AND busm.parent_id='".$menu['id']."'
                ORDER BY busm.menu_sequence
                ";

                $submenus = $db->query($sql)->fetchAll();
                foreach($submenus as $submenu){
                    if(strtolower(str_replace(" ","_",$submenu['title']))==$checkmenu){
                        $display_submenu = 1;
                    }  
                }

                ?>
            <ul class="nav nav-group-sub" <?php echo($display_submenu==1) ? "style='display:block'" : "" ?>  <?php //echo("style='display:block'") ?>  data-submenu-title="ECharts library">
                <?php foreach($submenus as $submenu){ ?>

			 
                    <li class="nav-item "><a href="../../admin/<?=$submenu['menu_link']?>" class="nav-link <?php echo(strtolower(str_replace(" ","_",$submenu['title']))==$checkmenu) ? "active" : "" ?>">
                    <?=$submenu['title']?></a></li>
			 
                <?php } ?>
            </ul>
            <?php $display_submenu = 0; ?>
            <?php } ?>
        </li>
		
	
        <?php //}?> 	
		
        <?php }?> 
    </ul>
</div>
<!-- /main navigation -->
 