<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Emne.php';

  $database = new Database();
  $db = $database->connect();

  $emne = new Emne($db);

  $data = json_decode(file_get_contents("php://input"));

  $emne->emnenavn = $data->emnenavn;

  if($emne->delete()) {
    echo json_encode(
      array('message' => 'Emne deleted')
    );
  } else {
    echo json_encode(
      array('message' => 'Emne not deleted')
    );
  }
