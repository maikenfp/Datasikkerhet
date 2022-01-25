<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Emne.php';

  $database = new Database();
  $db = $database->connect();

  $emne = new Emne($db);

  $data = json_decode(file_get_contents("php://input"));

  $emne->emnenavn = $data->emnenavn;
  $emne->emnekode = $data->emnekode;
  $emne->pinkode = $data->pinkode;

  if($emne->create()) {
    echo json_encode(
      array('message' => 'Emne Created')
    );
  } else {
    echo json_encode(
      array('message' => 'Emne Not Created')
    );
  }
