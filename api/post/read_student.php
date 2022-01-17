<?php 
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';

  $database = new Database();
  $db = $database->connect();

    $query = 'SELECT * FROM categories';
    
    $stmt = $db->query($query);

    $all = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($all) {
        foreach ($all as $all) {
            echo $all['name'] . "\n";
            echo $all['id'] . "\n";
        }
    }


    // IKKE GJØR NOE HER
