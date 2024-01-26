<?php
include('config.php');


$query = (isset($_POST['query']) ? $_POST['query'] : "");

if(isset($_POST["query"]))  
{  
    $output = '';  
    // Use the $link variable for the database connection
    $sql = "SELECT * FROM base_content WHERE content_title LIKE '%".$link->real_escape_string($_POST["query"])."%' AND is_active='1' ORDER BY content_title";  
    $result = $link->query($sql);
    $output = '<ul class="list-group">';  
    if($result->num_rows > 0)  
    {  
        while($row = $result->fetch_assoc())  
        {  
            $output .= '<a class="list-group-item list-group-item-action" href="'.$row["url"].'" style="color: black;">'.$row["content_title"].'</a>';  
        }  
    }  
    else  
    {  
        $output .= '<li class="list-group-item">Content Not Found</li>';  
    }  
    $output .= '</ul>';  
    echo $output;  
}   
?>
