<?php
include('../main_resource.php');


$sqlYears = "SELECT DISTINCT YEAR(created_date) as year FROM posts ORDER BY year DESC";
$resultYears = $db->query($sqlYears);
$years = $db->fetchAll($resultYears);

$sqlMonths = "SELECT DISTINCT MONTH(created_date) as month FROM posts ORDER BY month";
$resultMonths = $db->query($sqlMonths);
$months = $db->fetchAll($resultMonths);


$selectedYear = isset($_GET['year']) ? $_GET['year'] : '';
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : '';
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : ''; 

$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'created_date';
$sortDirection = isset($_GET['direction']) && in_array($_GET['direction'], ['asc', 'desc']) ? $_GET['direction'] : 'desc';

if (!in_array($sortColumn, ['title', 'created_date'])) {
    $sortColumn = 'created_date';
}
// Create the SQL query with optional year and month filters
$sql = "SELECT * FROM posts WHERE category='page_detail'";
if (!empty($selectedYear)) {
    $sql .= " AND YEAR(created_date) = " . intval($selectedYear);
    if (!empty($selectedMonth)) {
        $sql .= " AND MONTH(created_date) = " . intval($selectedMonth);
    }
}


if (!empty($searchQuery)) {
    $sql .= " AND title LIKE '%" . addslashes($searchQuery) . "%'"; 
}
$sql .= " ORDER BY $sortColumn $sortDirection";

$resultData = $db->query($sql);
$currdata = $db->fetchAll($resultData);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Campus</title>
</head>
<body>

<?php include('../main_navbar.php'); ?>

<div class="page-content">
    <div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg shadow-sm">
        <div class="sidebar-content">
            <?php include('../user_menu.php'); ?>
            <?php include('../main_navigation.php'); ?>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="content-inner">
            <?php include('../page_header.php'); ?>

            <div class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Page</h5>
                                <div class="header-elements">
                                    <form method="GET" id="filterForm" class="form-inline">
                                        <div style="display: flex;">


                                            <input type="text" name="search" class="form-control mr-2" placeholder="Search by title" value="<?= htmlspecialchars($searchQuery); ?>">

                                            <select name="year" id="yearSelector" class="form-control mr-2">
                                                <option value=""> -- select year --</option>
                                                <?php foreach ($years as $year): ?>
                                                    <?php $selected = ($year['year'] == $selectedYear) ? "selected" : ""; ?>
                                                    <option value="<?= $year['year'] ?>" <?= $selected ?>><?= $year['year'] ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                            <select name="month" id="monthSelector" class="form-control mr-2">
                                                <option value=""> -- select month --</option>
                                                <?php foreach ($months as $month): ?>
                                                    <?php $monthName = date('F', mktime(0, 0, 0, $month['month'], 10)); ?>
                                                    <?php $selected = ($month['month'] == $selectedMonth) ? "selected" : ""; ?>
                                                    <option value="<?= $month['month'] ?>" <?= $selected ?>><?= $monthName ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-container">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Title</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php if (!empty($currdata)): ?>
                                                <?php foreach ($currdata as $data): ?>
                                                    <tr>
                                                        <td style="text-align: center; width: 10px"><?= $no++; ?></td>
                                                        <td><a target="_blank" href="../../<?= htmlspecialchars($data['path']); ?>"><?= htmlspecialchars($data['title']); ?></a></td>
                                                        <td><?= $data['is_active'] == 1 ? 'Active' : 'Inactive' ?></td>
                                                        <td><?= htmlspecialchars($data['created_date']); ?></td>
                                                        <td>
                                                            <a href="edit.php?id=<?= $data['id']?>" class="btn btn-primary">Edit</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" style="text-align: center;">No data available</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include('../main_footer.php'); ?>
        </div>
    </div>
</div>
<script>

    $('table').DataTable();
 
</script>

</body>
</html>