<?php

function getMenuItems($db, $parentId = null) {
    $sql = is_null($parentId)
        ? "SELECT * FROM posts WHERE is_menu = 1 AND parent_id IS NULL ORDER BY no_order ASC"
        : "SELECT * FROM posts WHERE is_menu = 1 AND parent_id = $parentId ORDER BY no_order ASC";

    $result = $db->query($sql);
    $items = [];

    while ($item = mysqli_fetch_assoc($result)) {
        $items[] = $item;
    }

    // Free the result set
    $db->free();

    foreach ($items as &$item) {
        $item['children'] = getMenuItems($db, $item['id']);
    }

    return $items;
}



$menus = getMenuItems($db, null); 

function buildMenuHTML($menuItems, $parentId = 0) {
    $html = $parentId === 0 ? '<ul class="navbar-nav ms-auto">' : '<ul class="dropdown-menu">'; 
    foreach ($menuItems as $item) {
        $hasChildren = !empty($item['children']);

        if ($hasChildren) {
            $html .= '<li class="nav-item dropdown" style="margin-right: 6px">';
            $html .= '<a class="nav-link " href="#" id="navbarDropdown' . $item['id'] . '" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
            $html .= $item['title'];
            $html .= '</a>';
            $html .= buildMenuHTML($item['children'], $item['id']);
        } else {
            $html .= '<li class="nav-item" style="margin-right: 6px">';
            $html .= '<a class="nav-link" href="/' . $item['path'] . '">' . $item['title'] . '</a>';
        }
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

$menusHTML = buildMenuHTML($menus);
?>

<!-- Main navbar -->
<div style="background:#1e272e; display: flex; justify-content: space-between; align-items: center;">
    <div style="padding-top: 7px; padding-left: 15px; padding-bottom: 7px;">
    <a class="hover-01" href="tel:6283802434392" style="color: white; text-decoration: none;">
    0838 0243 4392
</a>

    </div>
    <div style="padding-top: 7px; padding-bottom: 7px;">
        <a href="https://campus.com/search/" style="font-size: 16px; color: white; text-decoration: none;">
            <i class="fa fa-search"></i>
        </a> &nbsp;&nbsp;
        <a href="#" class="btn" style="font-size: 12px; color: white; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#modal_applynow">
            <i class="fa fa-plus mr-2"></i><b>Campus Online Application</b>
        </a>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-light" style="background-color: white; box-shadow: 0 2px 4px -1px rgba(0,0,0,0.1), 0 1px 2px -1px rgba(0,0,0,0.1);">
    <div class="container-fluid" style="margin-left: 20px; margin-right: 20px">
        <a href="/" class="navbar-brand" style="padding-top: 10px; padding-bottom: 10px;">
            <img src="/images/logo.png" alt="" style="width: auto; height: 70px">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php echo $menusHTML; ?>
        </div>
    </div>
</nav>