<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Teacher.php';

$database = new Database();
$db = $database->connect();

$teacher = new Teacher($db);

$result = $teacher->read();
$num = $result->rowCount();

if($num > 0) {
  $teacher_arr = array();

  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $teacher_item = array(
      'epost' => $epost,
      'navn' => $navn
    );

    array_push($teacher_arr, $teacher_item);
  }

  echo json_encode($teacher_arr);

} else {
  echo json_encode(
    array('message' => 'No teachers found')
  );
}