<?php
session_start();
include_once "db_conn.php";
// CHANGE TO THIS WHEN MERGED v
// include_once '../../config/Database.php';

$database = new Database();
$db = $database->connect();

if (isset($_POST['brukerEpost']) && isset($_POST['brukerPassord'])) {
    function validate($data){
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);

       return $data;
    }

    $brukerEpost = validate($_POST['brukerEpost']);
    $brukerPassord = validate($_POST['brukerPassord']);

    if (empty($brukerEpost)) {
        header("Location: login_STUDENT.php?error=Du må skrive inn e-post!");
        exit();
    } else if(empty($brukerPassord)){
        header("Location: login_STUDENT.php?error=Du må skrive inn passord!");
        exit();
    } else{
        $sql = "SELECT * FROM student WHERE `e-post`='$brukerEpost' AND passord='$brukerPassord'";

        $stmt = $db->query($sql);
        $result = $db->prepare("SELECT SQL_CALC_FOUND_ROWS student_id, passord, studieretning, studiekull, `e-post`, navn FROM student");
        $result->execute();
        $result = $db->prepare("SELECT FOUND_ROWS()");
        $result->execute();
        $row_count = $result->fetchColumn();

        if ($row_count === 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['e-post'] === $brukerEpost && $row['passord'] === $brukerPassord) {

                echo "Du er logget inn!";

                $_SESSION['student_id'] = $row['student_id'];
                $_SESSION['studieretning'] = $row['studieretning'];
                $_SESSION['studiekull'] = $row['studiekull'];
                $_SESSION['e-post'] = $row['e-post'];
                $_SESSION['navn'] = $row['navn'];

                // BYTT MED STUDENT (JOAKIM)
                header("Location: home_STUDENT.php");
                exit();
            } else{
                header("Location: login_STUDENT.php?error=Feil brukernavn eller passord");
                exit();
            }
        } else{
            header("Location: login_STUDENT.php?error=Feil brukernavn eller passord");
            exit();
        }
    }
} else{
    header("Location: login_STUDENT.php");
    exit();
}
?>
