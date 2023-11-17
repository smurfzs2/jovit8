
<?php  include ("../connection.php"); 

$requestData= $_REQUEST;
$sqlData = isset($requestData['query']) ? $requestData['query'] : "";
$totalRecords = isset($requestData['totalRecords']) ? $requestData['totalRecords'] : "";

$data = array();


$sql= $sqlData;
$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length']."  ";
$queryResult = $connectionString->query($sql) or die ($connectionString->error);


if($queryResult AND $queryResult->num_rows > 0)
{
 
    while($resultData = $queryResult->fetch_assoc())
    {
       

        $id = $resultData['id'];
        
        $firstName = $resultData['firstName'];
        $lastName = $resultData['lastName'];
        $gender = $resultData['gender'] == 1 ? "Male":"Female";
        $birthdate = date("F d, Y", strtotime($resultData['birthDate']));
        $address = $resultData['address'];
        $department = $resultData['departmentName'];

        $button="";
        
        $button.= "<a href='jovit_update.php?id=" . $resultData['id'] ."' name = 'update' class = 'btn btn-success update'><i class = 'fa-solid fa-pen-to-square'></i></a>";

        $button.= "<a href = 'jovit_delete.php?id=" . $resultData['id'] ."' name = 'delete' class = 'btn btn-danger delete'><i class = 'fa-solid fa-trash'></i></a>";
        
        
        $nestedData = Array();
        
        $nestedData[] = ++$requestData['start']; 
        $nestedData[] = $firstName; 
        $nestedData[] = $lastName; 
        
        $nestedData[] = $gender; 
        $nestedData[] = $birthdate;  
        $nestedData[] = $address;
      
        $nestedData[] = $department;
        $nestedData[] = $button; 
        $data[] = $nestedData;

       
       
    }
}



$json_data = array(
    "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
    "recordsTotal"    => intval($totalRecords ),  // total number of records
    "recordsFiltered" => intval($totalRecords ), // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data"            => $data   // total data array
);

echo json_encode($json_data);  // send data as json format

?>

