<?php
    session_start();
    require '../config/Database.php';

    function validate($data) {
        $data = preg_replace('/[^A-Za-z0-9@. ]/i', '', $data);
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    $pw1 = validate(strip_tags($_POST['pw1']));
    $pw2 = validate(strip_tags($_POST['pw2']));

    $epost = $_SESSION['epost'];
    $pass_hash = password_hash($pw2, PASSWORD_DEFAULT);

    if($pw1 === $pw2){
        $database = new Database();
        $db = $database->connect();

        $query = "UPDATE foreleser SET passord='$pass_hash' WHERE epost='$epost'";
        $stmt = $db->query($query);
   
        echo "<script>";
        echo "alert('Passordet er tilbakestilt');";
        echo "</script>";
        echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
    } else {
        header("Location: new-password.php?error=Passordene er ikke like!");
    }

?>
