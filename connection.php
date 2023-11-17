<?php 
$serverName = "localhost";
$userName = "root";
$password = "arktechdb";
$databaseName = "ojtDatabase";
$connectionString = mysqli_connect($serverName,$userName,$password,$databaseName);

if ($connectionString->connect_error)
{
    echo "connection error" . $connectionString->connect_error;
}

?>