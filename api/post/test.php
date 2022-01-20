<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $post = new Post($db);

  // Blog post query
  $result = $->read();
  // Get row count
  $num = $result->rowCount();

  // Check if any posts
  if($num > 0) {
}

    echo json_encode($posts_arr);

  } else {
    echo json_encode(
      array('message' => 'No Posts Found')
    );
  }