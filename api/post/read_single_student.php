<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';

  //$param = $_GET['name'];

  $name = isset($_GET['name']) ? $_GET['name'] : die();
  $database = new Database();
  $db = $database->connect();

  $query = 'SELECT * FROM categories WHERE name = ? ';

  $stmt = $db->prepare($query); 
  $stmt->bindParam(1, $name);
  $stmt->execute();

  $row = $stmt->fetch();

  echo $row['name'];
  echo "\n";
  echo $row['id'];


