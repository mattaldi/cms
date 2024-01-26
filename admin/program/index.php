<?php
include('../main_resource.php');

// Fetch distinct years and months from the database
$sqlYears = "SELECT DISTINCT YEAR(created_date) as year FROM posts ORDER BY year DESC";
$resultYears = $db->query($sqlYears);
$years = $db->fetchAll($resultYears);

$sqlMonths = "SELECT DISTINCT MONTH(created_date) as month FROM posts ORDER BY month";
$resultMonths = $db->query($sqlMonths);
$months = $db->fetchAll($resultMonths);

// Get year and month from GET request if set
$selectedYear = isset($_GET['year']) ? $_GET['year'] : '';
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : '';
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : ''; 

$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'created_date';
$sortDirection = isset($_GET['direction']) && in_array($_GET['direction'], ['asc', 'desc']) ? $_GET['direction'] : 'desc';

if (!in_array($sortColumn, ['title', 'created_date'])) {
    $sortColumn = 'created_date';
}
// Create the SQL query with optional year and month filters
$sql = "SELECT * FROM posts WHERE category='program_detail'";
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
                                <h5 class="card-title">Blog</h5>
                                <div class="header-elements">
                                    <form method="GET" id="filterForm" class="form-inline">
                                        <div style="display: flex;">

                                            <!-- Add Button -->
                                            <!-- <a href="add.php" class="btn btn-primary mr-2">Add</a> -->

                                            <input type="text" name="search" class="form-control mr-2" placeholder="Search by title" value="<?= htmlspecialchars($searchQuery); ?>">

                                            <!-- Year Selector -->
                                            <select name="year" id="yearSelector" class="form-control mr-2">
                                                <option value=""> -- select year --</option>
                                                <?php foreach ($years as $year): ?>
                                                    <?php $selected = ($year['year'] == $selectedYear) ? "selected" : ""; ?>
                                                    <option value="<?= $year['year'] ?>" <?= $selected ?>><?= $year['year'] ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                            <!-- Month Selector -->
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
                                                <th><a href="?sort=title&direction=<?= $sortDirection == 'asc' ? 'desc' : 'asc' ?>">Title</a></th>
                                                <th><a href="?sort=created_date&direction=<?= $sortDirection == 'asc' ? 'desc' : 'asc' ?>">Date</a></th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php if (!empty($currdata)): ?>
                                                <?php foreach ($currdata as $data): ?>
                                                    <tr>
                                                        <td style="text-align: center"><?= $no++; ?></td>
                                                        <td><a target="_blank" href="../../<?= htmlspecialchars($data['path']); ?>"><?= htmlspecialchars($data['title']); ?></a></td>
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
    // Debounce function
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };

    // Save focus state in localStorage
    function saveFocusState() {
        localStorage.setItem('searchFocus', 'true');
    }

    // Apply focus state after page reload
    function applyFocusState() {
        var searchFocus = localStorage.getItem('searchFocus');
        if (searchFocus === 'true') {
            var searchInput = document.querySelector('input[name="search"]');
            searchInput.focus();
            localStorage.removeItem('searchFocus'); // Clear the focus state
        }
    }

    // Applying debounce to the search input
    var searchInput = document.querySelector('input[name="search"]');
    searchInput.addEventListener('input', debounce(function() {
        saveFocusState();
        document.getElementById('filterForm').submit();
    }, 500)); // Adjust the time (500ms) as needed

    // Existing event listeners
    document.getElementById('yearSelector').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    document.getElementById('monthSelector').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Apply focus state on page load
    applyFocusState();




    document.addEventListener('DOMContentLoaded', function() {
        var queryString = window.location.search;
        var urlParams = new URLSearchParams(queryString);

        // Update all header links
        document.querySelectorAll('th a').forEach(function(link) {
            var currentSort = urlParams.get('sort');
            var currentDirection = urlParams.get('direction');
            var newSort = link.href.split('sort=')[1].split('&')[0];

            // If current sort is this column, toggle the direction, otherwise default to asc
            var newDirection = currentSort === newSort && currentDirection === 'asc' ? 'desc' : 'asc';

            urlParams.set('sort', newSort);
            urlParams.set('direction', newDirection);
            link.href = '?' + urlParams.toString();
        });
    });





</script>

</body>
</html>
