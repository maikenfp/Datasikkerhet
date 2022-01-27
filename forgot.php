
<?php
include_once 'config/Database.php';

$database = new Database();
$db = $database->connect();


$epost = isset($_POST['epost']) ? trim($_POST['epost']) : '';

//The simple SQL query that we will be running.
$sql = "SELECT 'id', `epost` FROM `student` WHERE `epost` = :epost";

//Prepare our SELECT statement.
$statement = $db->prepare($sql);

//Bind the $name variable to our :name parameter.
$statement->bindValue(':epost', $epost);

//Execute the SQL statement.
$statement->execute();

//Fetch our result as an associative array.
$userInfo = $statement->fetch(PDO::FETCH_ASSOC);

//If $userInfo is empty, it means that the submitted email
//address has not been found in our users table.
if(empty($userInfo)){
    echo 'That email address was not found in our system!';
    exit;
}

//The user's email address and id.
$userEmail = $userInfo['epost'];
$userId = $userInfo['id'];

//Create a secure token for this forgot password request.
$token = openssl_random_pseudo_bytes(16);
$token = bin2hex($token);

//Insert the request information
//into our password_reset_request table.

//The SQL statement.
$insertSql = "INSERT INTO password_reset_request
              (user_id, date_requested, token)
              VALUES
              (:user_id, :date_requested, :token)";

//Prepare our INSERT SQL statement.
$statement = $db->prepare($insertSql);

//Execute the statement and insert the data.
$statement->execute(array(
    "user_id" => $userId,
    "date_requested" => date("Y-m-d H:i:s"),
    "token" => $token
));

//Get the ID of the row we just inserted.
$passwordRequestId = $db->lastInsertId();


//Create a link to the URL that will verify the
//forgot password request and allow the user to change their
//password.
$verifyScript = 'http://localhost/datasikkerhet/change.php';

//The link that we will send the user via email.
$linkToSend = $verifyScript . '?uid=' . $userId . '&id=' . $passwordRequestId . '&t=' . $token;

//Print out the email for the sake of this tutorial.
echo $linkToSend;