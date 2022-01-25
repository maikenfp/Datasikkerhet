<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Emne.php';

$database = new Database();
$db = $database->connect();

$emne = new Emne($db);

$result = $emne->read();
$num = $result->rowCount();

if($num > 0) {
  $emne_arr = array();

  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $emne_item = array(
      'emnekode' => $emnekode,
      'emnenavn' => $emnenavn
    );

    array_push($emne_arr, $emne_item);
  }

  echo json_encode($emne_arr);

} else {
  echo json_encode(
    array('message' => 'No emner found')
  );
}