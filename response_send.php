<?php
    include_once 'db_connection.php';

if(!$conn) {
    echo 'Not connected to server!';
}

if(!mysqli_select_db($conn,'exampleds')) {
    echo 'Database not selected!';
}


$reply = $_POST['reply'];

$sql = "UPDATE emne SET reply='$reply' WHERE emne_id=88";



if ($conn->query($sql) === TRUE) {
    echo "Message: &#34;$reply&#34; added to database";
  } else {
    echo "Error updating record: " . $conn->error;
  }
  
  $conn->close();


?>