<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Student.php';

  $database = new Database();
  $db = $database->connect();

  $student = new Student($db);

  $data = json_decode(file_get_contents("php://input"));

  $student->spørsmål = $data->spørsmål;
  $student->epost = $data->epost;
  $student->emnenavn = $data->emnenavn;


  if($student->createMessage()) {
    echo json_encode(
      array('message' => 'Message Created')
    );
  } else {
    echo json_encode(
      array('message' => 'Message Not Created')
    );
  }
