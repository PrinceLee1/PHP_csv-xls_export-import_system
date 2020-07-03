<?php
    $conn = new mysqli("localhost","dbusername","****","dbname") ;


if($conn)
{require_once 'index.php';
    $try = $_SESSION['user_first_name']. '--'.$_SESSION['user_last_name'];
    $file = $_FILES['csvfile']['tmp_name'];
    $handle = fopen($file,"r");

    $i = 0;
    while(($content = fgetcsv($handle,1000,","))!==false)
     {
        $table = 'mydata';
        if($i == 0){
        $value0 = $content[0];
        // print_r($name);exit;

        $value1 = $content[1];
        $value2 = $content[2];
        $value3 = $content[3];
        $user = 'user_id';
        $query = "CREATE TABLE $table ($value0 VARCHAR (50), $value1 VARCHAR (50), $value2 VARCHAR (500),$value3 VARCHAR (50),$user VARCHAR (20));";
        mysqli_query($conn,$query);
        }else{
             $query = "INSERT INTO $table ($value0,$value1,$value2,$value3,$user) VALUES('$content[0]','$content[1]','$content[2]','$content[3]','$try');";
             echo '<p style="background:green;color:white;">Your file have been saved succesfully</p>', "<br>";
           $result=  mysqli_query($conn,$query);
           if(!$result)
           {
             echo "<script type=\"text/javascript\">
                 alert(\"Invalid File:Please Upload CSV File.\");
                 window.location = \"index.php\"
                 </script>";    
           }
           else {
               echo "<script type=\"text/javascript\">
               alert(\"CSV File has been successfully Imported.\");
               window.location = \"index.php\"
             </script>";
   
        }
    }
        $i++;
    }
  

} 
else
{
    echo "Connection Failed";
}

 
?>
