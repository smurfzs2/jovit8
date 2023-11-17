<!-- filter -->


<?php
include ("../connection.php");

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

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="...">
    <title>Document</title>
</head>

<body>
    <!-- searchbox -->
    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col-md-12">
                <form action="jovit_index.php" method="POST" id="formSubmit">
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


                        <select class="form-control" name="department" form="formSubmit">
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
                <table class="table table-bordered table-striped table hover">
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

                    <tr>
                        <?php

                        $query_run = mysqli_query($connectionString, $query);

                        if (mysqli_num_rows($query_run) > 0) {
                            $idnumber = 1;
                            while ($details = mysqli_fetch_assoc($query_run)) {
                        ?>
                    <tr>
                        <td><?= $idnumber++; ?></td>
                        <td><?= $details['firstName']; ?></td>
                        <td><?= $details['lastName']; ?></td>
                        <td><?= $details['gender'] == '1' ? "Male" : "Female"; ?></td>
                        <td><?= $details['address']; ?></td>
                        <td><?= date('F j, Y', strtotime($details['birthDate'])); ?></td>
                        <td><?= $details['departmentName']; ?></td>
                        <td>

                            <a href="jovit_edit.php?id=<?= $details['id']; ?>" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="jovit_code.php" method="POST" class="d-inline">
                                <button type="submit" name="delete_data" value="<?= $details['id']; ?>" class="btn btn-danger btn-sm"><i class="delete fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
            <?php
                            }
                        } else {
                            echo "<h5> No Record Found </h5>";
                        }
            ?>
            </tr>

            <!-- Modal -->
            <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Add New</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container mt-5">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="card-body">
                                            <form action="jovit_code.php" method="POST">
                                                <div class="mb-3">
                                                    <label>First Name</label>
                                                    <input type="text" name="firstName" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Last Name</label>
                                                    <input type="text" name="lastName" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Gender</label>
                                                    <select name="gender" class="form-select">
                                                        <option value="1">Male</option>
                                                        <option value="2">Female</option>
                                                    </select>

                                                </div>
                                                <div class="mb-3">
                                                    <label>Address</label>
                                                    <input type="text" name="address" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Birthdate:</label>
                                                    <input type="date" name="birthDate" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <!-- <a href="jovit_index.php" name ="save_details" class = "btn btn-primary">SAVE</a> -->
                                                    <button type="submit" name="save_details" class="btn btn-primary">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                // JavaScript to trigger the modal
                var myModal = new bootstrap.Modal(document.getElementById('exampleModalLong'));

                document.getElementById('exampleModalLongBtn').addEventListener('click', function() {
                    myModal.show();
                });
            </script>




            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</body>

</html>