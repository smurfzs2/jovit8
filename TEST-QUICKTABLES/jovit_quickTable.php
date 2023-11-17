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
    <meta name="viewport" content="width=dSevice-width, initial-scale=1.0">
    <!-- Boothstrap -->
    <meta name="viewport" content="width=dSevice-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <!-- Font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Data Tables -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/scroller/2.1.0/js/dataTables.scroller.min.js"></script>


   
    <title>Quick Table</title>
</head>

<body>

    <?php

    if (isset($_POST['search'])) {

        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $birthday = $_POST['birthday'];
        $department = $_POST['department'];


        $query = "SELECT * FROM `tbl_jovit` INNER JOIN `hr_department` ON tbl_jovit.departmentId=hr_department.departmentId WHERE ";


        if ($firstName != "") {
            $query .= " firstName LIKE '%$firstName%' AND ";
        }
        if ($lastName != "") {
            $query .=  " lastName LIKE '%$lastName%' AND ";
        }

        if ($birthday != "") {
            $query .= " birthDate LIKE '%$birthday%' AND ";
        }

        if ($address != "") {
            $query .= " address LIKE '%$address%' AND ";
        }

        if ($gender != "") {
            $query .= " gender LIKE '%$gender%' AND ";
        }

        if ($department != "") {
            $query .= " departmentName LIKE '%$department%' AND ";
        }

        $query = substr($query, 0, -4);
    } else {
        $query = "SELECT * FROM `tbl_jovit` INNER JOIN `hr_department` ON tbl_jovit.departmentId=hr_department.departmentId";
    }


    $records = $connectionString->query($query);
     $totalRecords = $records->num_rows;


    function getDepartment($db)
    {
        $query = "SELECT * FROM `hr_department` ORDER BY departmentName ASC";
        return $result = mysqli_query($db, $query);
    }


    function filterTable($query)
    {
        $connect = mysqli_connect("localhost", "root", "arktechdb", "ojtDatabase");

        if (!$connect) {
            die("Connection failed: " . mysqli_connect_error());
        }

        echo "Query being executed: " . $query . "<br>"; // Output the query being executed

        $filter_Result = mysqli_query($connect, $query);
        return $filter_Result;
    }
    ?>


    <!-- searchbox -->
    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col-md-12">
                <form action="jovit_quickTable.php" method="POST" id="formSubmit">
                    <div class="input-group">
                        <input type="search" class="form-control" placeholder="Search by First Name" name="firstName" value="<?php if (isset($_POST['firstName'])) {
                                                                                                                                    echo $_POST['firstName'];
                                                                                                                                } ?>" form="formSubmit" />
                        <input type="search" class="form-control" placeholder="Search by Last Name" name="lastName" value="<?php if (isset($_POST['lastName'])) {
                                                                                                                                echo $_POST['lastName'];
                                                                                                                            } ?>" form="formSubmit" />
                        <input type="search" class="form-control" placeholder="Search by Address" name="address" value="<?php if (isset($_POST['address'])) {
                                                                                                                            echo $_POST['address'];
                                                                                                                        } ?>" form="formSubmit" />
                        <select class="form-control" name="gender" form="formSubmit">
                            <option value="" selected disabled>Search by Gender
                                <?php
                                if (isset($_POST['gender'])) {
                                    echo $_POST['gender'] ? 'Female' : 'Male';
                                } else {
                                    echo "Gender";
                                }
                                ?>
                            </option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                            </option>

                        </select>
                        <input type="date" class="form-control" placeholder="Search by Birthday" name="birthday" value="<?php if (isset($_POST['birthday'])) {
                                                                                                                            echo $_POST['birthday'];
                                                                                                                        } ?>" form="formSubmit" />


                        <select class="form-control" name="departmentName" form="formSubmit">
                            <option value="" disabled <?php if (!isset($_POST['departmentName'])) echo 'selected'; ?>>Department</option>
                            <?php
                            $department = getDepartment($connectionString);

                            if (mysqli_num_rows($department) > 0) {
                                foreach ($department as $item) {
                            ?>
                                    <option value="<?= $item['departmentName']; ?>" <?php if (isset($_POST['departmentName']) && $_POST['departmentName'] === $item['departmentName']) echo 'selected'; ?>>
                                        <?= $item['departmentName']; ?>
                                    </option>
                            <?php
                                }
                            }
                            ?>
                        </select>

                        <button type="submit" name="search" class="btn btn-dark" form="formSubmit">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TABLES -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                Table Data
            </div>
            <div class="card-body">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-dark mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Add
                </button>
                <table id="userTable"class="table table-bordered table-striped table hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>FIRST NAME</th>
                        <th>LAST NAME</th>
                        <th>GENDER</th>
                        <th>BIRTH DATE</th>
                        <th>ADDRESS</th>
                        <th>DEPARTMENT</th>
                        <th>ACTIONS</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>


                <script>
        jQuery(document).ready(function($)
        {
            $('#userTable').DataTable({
                
                "bLengthChange": false,
                "processing"  : true,
                "searching" : true,
                "serverSide": true,
                "ordering" : false,
                "paging" : false,
                "info" : false,
                "sDom" : "lrti",
                "ajax" : {
                    url: "jovit_ajax.php", // json datasource (another file)
                    type: "POST", // method, the default is GET
                    data: {
                        "query": "<?php echo $query; ?>",
                        "totalRecords": "<?php echo $totalRecords; ?>",
                    },
                    error: function(data){ // error handling
                        console.log(data);
                    }
                },

                paging: true,
                deferRender: true,
                scrollY: 450,
                scrollcollapse: false,
                scroller: true,
                buttons: ['createState', 'savedStates']
            });

            {}
        });


        function refreshPage(){
            window.location.reload();
        }
    </script>
</body>
</html>