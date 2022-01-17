<?php
session_start();
include "db_connection.php";

if (isset($_POST['brukerNavn']) && isset($_POST['brukerPassord'])) {
    function validate($data){
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);

       return $data;
    }

    $brukerNavn = validate($_POST['brukerNavn']);
    $brukerPassord = validate($_POST['brukerPassord']);

    if (empty($brukerNavn)) {
        header("Location: index.php?error=Du må skrive inn brukernavn!");
        exit();
    } else if(empty($brukerPassord)){
        header("Location: index.php?error=Du må skrive inn passord!");
        exit();
    } else{
        $sql = "SELECT * FROM brukere WHERE brukernavn='$brukerNavn' AND passord='$brukerPassord'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);

            if ($row['brukernavn'] === $brukerNavn && $row['passord'] === $brukerPassord) {

                echo "Du er logget inn!";

                $_SESSION['brukernavn'] = $row['brukernavn'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];

                header("Location: home.php");
                exit();
            } else{
                header("Location: index.php?error=Feil brukernavn eller passord");
                exit();
            }
        } else{
            header("Location: index.php?error=Feil brukernavn eller passord");
            exit();
        }
    }
} else{
    header("Location: index.php");
    exit();
}
