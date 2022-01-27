<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Student.php';

$database = new Database();
$db = $database->connect();

$student = new Student($db);

$result = $student->read();
$num = $result->rowCount();

if($num > 0) {
  $student_arr = array();

  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $student_item = array(
      'studieretning' => html_entity_decode($studieretning),
      'studiekull' => $studiekull,
      'epost' => $epost,
      'navn' => $navn
    );

    array_push($student_arr, $student_item);
  }

  echo json_encode($student_arr);

} else {
  echo json_encode(
    array('message' => 'No students found')
  );
}