<?php /*
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');

  include_once '../../config/Database.php';

  $database = new Database();
  $db = $database->connect();

  $data = json_decode(file_get_contents("php://input"));


  $query = 'INSERT INTO categories SET
  name = :name ';

$db = $data->name;


  $stmt = $db->prepare($query); 
  $stmt->bindParam(':name', $name);
  if($stmt->execute()) {
    return true;
    }
    printf("Error: %s.\n", $stmt->error);

    return false;
*/
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Post.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $post = new Post($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  $post->name = $data->name;

  // Create post
  if($post->create()) {
    echo json_encode(
      array('message' => 'Post Created')
    );
  } else {
    echo json_encode(
      array('message' => 'Post Not Created')
    );
  }
