<?php
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);
ini_set("display_errors", "on");

include ("../connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete</title>
</head>
<body>
        <!-- DELETE QUERY -->

            <?php 
            
            $id = $_GET['id'];
            $deleteQuery = "DELETE FROM tbl_jovit WHERE id = '$id'";


            if ($connectionString->query($deleteQuery))
            {
                /* header("location: index.php"); */
                echo header("location: jovit_quickTable.php");
                echo '<script>alert("Record Delete Succesfully");</script>';
            }
            else
            {
                echo "Error".mysqli_error($connectionString);
            }
            
            ?>

        <!-- END OF DELETE QUERY -->
</body>
</html>