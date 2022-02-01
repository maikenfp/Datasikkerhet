<?php
    session_start();
    require 'config/Database.php';

    $pw1 = $_POST['pw1'];
    $pw2 = $_POST['pw2'];

    $epost = $_SESSION['epost'];

    if($pw1 === $pw2){
        $database = new Database();
        $db = $database->connect();
    
        $query = "UPDATE foreleser SET passord='$pw2' WHERE epost='$epost'";
        $stmt = $db->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        echo '<script>alert("Passordet er tilbakestilt!")</script>';
        echo '<script>window.location.href = "index.php"</script>';
        //header("Location: index.php");
    } else {
        header("Location: new-password.php?error=Passordene er ikke like!");
    }

?>

