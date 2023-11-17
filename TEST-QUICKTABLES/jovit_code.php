<?php
session_start();

require("../connection.php");
if(isset($_POST['delete_data']))
{
    $id = mysqli_real_escape_string($connectionString, $_POST['delete']);

    $query = "DELETE FROM tbl_jovit WHERE id='$id' ";
    $query_run = mysqli_query($connectionString, $query);

    if($query_run)
    {
        $_SESSION['message'] = " Deleted Successfully";
        header("Location: jovit_quickTable.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Data Not Deleted";
        header("Location: jovit_quickTable.php");
        exit(0);
    }
}

if(isset($_POST['update_details']))
{
    $id = mysqli_real_escape_string($con, $_POST['id']);

    $firstName = mysqli_real_escape_string($connectionString, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($connectionString, $_POST['lastName']);
    $gender = mysqli_real_escape_string($connectionString, $_POST['gender']);
    $address = mysqli_real_escape_string($connectionString, $_POST['address']);
    $birthDate = mysqli_real_escape_string($connectionString, $_POST['birthDate']);

    $query = "UPDATE tbl_jovit SET firstName='$firstName', lastName='$lastName', gender='$gender', address='$address' WHERE id='$id' ";
    $query_run = mysqli_query($connectionString, $query);

    if($query_run)
    {
        $_SESSION['message'] = "DataUpdated Successfully";
        header("Location: jovit_quickTable.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Data Not Updated";
        header("Location: jovit_quickTable.php");
        exit(0);
    }

}


if(isset($_POST['save_details']))
{
    $firstName = mysqli_real_escape_string($connectionString, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($connectionString, $_POST['lastName']);
    $gender = mysqli_real_escape_string($connectionString, $_POST['gender']);
    $address = mysqli_real_escape_string($connectionString, $_POST['address']);
    $birthDate = mysqli_real_escape_string($connectionString, $_POST['birthDate']);

    $query = "INSERT INTO tbl_jovit (firstName, lastName, gender, address, birthDate) VALUES ('$firstName','$lastName','$gender','$address', '$birthDate')";

    $query_run = mysqli_query($connectionString, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Created Successfully";
        header("Location: jovit_index.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = " Not Created";
        header("Location: jovit_addData.php");
        exit(0);
    }
}

?>