<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus - News</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Other CSS Libraries -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">


<?php

require_once '../../lib/DatabaseExceptions.php';
require_once '../../lib/DBSettings.php';
require_once '../../lib/DBClass.php';

try {
    $db = new DBClass();
    $table = 'posts';
    $columns = ['id', 'title', 'category', 'created_date'];
    $where = ['category' => 'news_detail'];
    $options = ['orderBy' => 'id DESC'];

    $result = $db->select($table, $columns, $where, $options);

    $data_content = $db->fetchAll($result);

    // Initialize arrays to hold unique years and months
    $years = [];
    $months = [];

    // Populate years and months arrays
    foreach ($data_content as $row) {
        $date = new DateTime($row['created_date']);
        $years[$date->format('Y')] = true;
        $months[$date->format('m')] = true;
    }

    // If no posts found
    if (empty($data_content)) {
        echo "<p>No news posts found.</p>";
        return;
    }

    // Sort years and months
    ksort($years);
    ksort($months);

?>    
</head>
<body>
<?php include('../nav.php');?>
<?php
    // Add an ID to the table for DataTables initialization
    echo "<table id='newsTable' class='table table-bordered table-striped'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Date</th>";
    echo "<th>Title</th>";
    echo "<th>Actions</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($data_content as $row) {
        echo "<tr>";
        echo "<td>" . $row['created_date'] . "</td>";
        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
        echo "<td>";
        echo "<button class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#detailModal' onclick='showDetails(" . $row['id'] . ")'>Details</button>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";

} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}

?>

<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Other JS Libraries -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<!-- DataTables Standard Script -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

<!-- Bootstrap 5 DataTables Integration Script -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#newsTable').DataTable({
                "lengthChange": false,
                "pageLength": 10,
                "responsive": true
            });

    // Custom filters HTML
    var yearFilterHtml = '<label>Year: <select id="yearFilter"><option value="">All Years</option>';
    <?php foreach (array_keys($years) as $year): ?>
        yearFilterHtml += '<option value="<?php echo $year; ?>"><?php echo $year; ?></option>';
    <?php endforeach; ?>
    yearFilterHtml += '</select></label>';

    var monthFilterHtml = '<label>Month: <select id="monthFilter"><option value="">All Months</option>';
    <?php foreach (array_keys($months) as $month): ?>
        monthFilterHtml += '<option value="<?php echo $month; ?>"><?php echo DateTime::createFromFormat('m', $month)->format('F'); ?></option>';
    <?php endforeach; ?>
    monthFilterHtml += '</select></label>';

    // Append custom filters to the DataTables controls
    var customFilters = $(yearFilterHtml + monthFilterHtml);
    $("#newsTable_wrapper .dataTables_filter").prepend(customFilters);

    // Align the entire search bar to the left
    $("#newsTable_wrapper .dataTables_filter").css('float', 'left');
    $("#newsTable_wrapper .dataTables_filter").css('text-align', 'left');

    // Custom filtering function
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var selectedYear = $('#yearFilter').val();
        var selectedMonth = $('#monthFilter').val();
        var date = new Date(data[0]);

        if ((selectedYear == '' || date.getFullYear().toString() === selectedYear) &&
            (selectedMonth == '' || (date.getMonth() + 1).toString() === selectedMonth)) {
            return true;
        }
        return false;
    });

    // Event listener for the year and month dropdowns
    $('#yearFilter, #monthFilter').change(function() {
        table.draw();
    });
});
</script>
<style>
        .dataTables_filter {
            float: left;
            text-align: left;
        }
        .dataTables_filter label {
            display: inline-flex;
            margin-right: 1em;
        }
    </style>