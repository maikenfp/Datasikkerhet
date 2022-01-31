<?php

session_start();
require 'config/Database.php';

$database = new Database();
$db = $database->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $responseID = $_POST['responseID'];
  $reply = $_POST['reply'];

  $sql = "UPDATE melding SET svar='$reply' WHERE melding_id='$responseID'";
  $result = $db->query($sql);

  if($result) {
    echo "<meta http-equiv='refresh' content='0;url=teachersubject.php'>";
  }

}
?>
