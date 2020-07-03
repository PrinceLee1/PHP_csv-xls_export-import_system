
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>

    <title>Document</title>
</head>
<style>
    table{
        margin-top:30px
    }
</style>
<body>

<div class="container">
<div class="row">
<?php
require_once 'index.php';
    // require 'insert.php';

$try = $_SESSION['user_first_name']. '--'.$_SESSION['user_last_name'];
// echo $try;exit;
    $con = new mysqli("localhost","dbusername","****","dbname") ;
    if($con){
    $Sql = "SELECT * FROM mydata WHERE user_id ='$try'";
    $result = mysqli_query($con, $Sql); 
    // print_r($result);exit; 
    if (mysqli_num_rows($result) > 0) {
     echo "<div class='table-responsive'><table id='myTable' class='table table-striped table-bordered'>
             <thead><tr><th>Cars</th>
                          <th>Chairs</th>
                          <th>Phone</th>
                          <th>Game</th>
                        </tr></thead><tbody>";
     while($row = mysqli_fetch_assoc($result)) {
        // print_r($row);exit;

         echo "<tr><td>" . $row['Cars']."</td>
                   <td>" . $row['chairs']."</td>
                   <td>" . $row['phone']."</td>
                   <td>" . $row['game']."</td></tr>";        
     }
    
     echo "</tbody></table></div>";
     
} else {
     echo '<div class="text-center" style="font-size:22px;background:whitesmoke;height:220px"><p style="transform:translateY(80px)"> Sorry, you have not Imported any file so far!</p></div>';
}
    }else{
        echo 'Sorry can connect to Server!';
    }
    
?>
        
     

    <div class="col-md-6">

            <form class="form-horizontal" action="" method="post" name="upload_excel"   
                      enctype="multipart/form-data">
                        
                                <input type="submit" name="Export" class="btn btn-success form-control" value="Export"/>                  
            </form> 
            <form method="post">  
                          <input type="submit" name="create_pdf" class="btn btn-danger form-control" value="Export as PDF" />  <i class="fa fa-pdf"aria-hidden="true"></i>
                     </form>
</div>
<div class="col-md-6">
                     <form action="insert.php" method="post" enctype="multipart/form-data">
    <input type="file" name="csvfile" required >
    <input type="submit" value="Import" class="btn btn-primary form-control" >
    </form>
          </div>

<!---PDF PART-->
<?php
   if(isset($_POST["Export"])){
     
    header('Content-Type: text/csv; charset=utf-8');  
    header('Content-Disposition: attachment; filename=mydata.csv');  
    $output = fopen("php://output", "w");  
    print_r($output);exit;
  $cg=  fputcsv($output, array('Cars', 'chairs', 'phone', 'game','user_id'));  
      print_r($cg);exit;

    $query = "SELECT * from mydata WHERE user_id='$try'";  
    $result = mysqli_query($con, $query);  
    while($row = mysqli_fetch_assoc($result))  
    {  
         fputcsv($output, $row);  
    }  
    fclose($output);  
} 
?>
     
<?php
            if(isset($_POST['create_pdf'])){
                require_once 'fpdf.php';
                $pdf = new FPDF();
    $con = new mysqli("localhost","dbusername","****","dbname") ;
                $result = mysqli_query($con, "SELECT Cars,chairs,phone,game FROM mydata WHERE user_id='$try'");
                if($result){
$header =mysqli_query($con,"SELECT UCASE(`COLUMN_NAME`) 
FROM `INFORMATION_SCHEMA`.`COLUMNS` 
WHERE `TABLE_SCHEMA`='crud' 
AND `TABLE_NAME`='mydata'
and `COLUMN_NAME` in ('Cars','chairs','phone','game')");
                ini_set("session.auto_start", 0);

                $pdf->AddPage();
                $pdf->SetFont('Arial','B',12);
                
                foreach($header as $heading) {
                    foreach($heading as $column_heading)
                        $pdf->Cell(50,10,'Heading',1);
                }
                foreach($result as $row) {
                    $pdf->Ln();
                    foreach($row as $column)
                        $pdf->Cell(50,10,$column,1);
                }
                ob_end_clean();

                $pdf->Output();
            }else{
                echo 'No data to export as PDF';
            }
}   
      
            ?> 
            </div>
            </div>
</body>
</html>

