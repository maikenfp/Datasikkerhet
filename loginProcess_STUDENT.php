<?php
session_start();
include_once 'config/Database.php';

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
        header("Location: login_STUDENT.php?error=Du må skrive inn epost!");
        exit();
    } else if(empty($brukerPassord)){
        header("Location: login_STUDENT.php?error=Du må skrive inn passord!");
        exit();
    } else if (!filter_var($brukerEpost, FILTER_VALIDATE_EMAIL)) {
        header("Location: login_STUDENT.php?error=Eposten er ikke gyldig!");
        exit();
    } else{
        $sql = 'SELECT * FROM student WHERE epost = :epost';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':epost', $brukerEpost, PDO::PARAM_STR);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row){
            if(password_verify($brukerPassord, $row['passord'])) {
                session_regenerate_id();

                $_SESSION['student_id'] = $row['student_id'];
                $_SESSION['navn'] = $row['navn'];
                $_SESSION['epost'] = $row['epost'];
                $_SESSION['studieretning'] = $row['studieretning'];
                $_SESSION['studiekull'] = $row['studiekull'];

                header("Location: student/index.php");
                exit();
            } else {
                header("Location: login_STUDENT.php?error=Feil epost eller passord");
                exit();
            }
        } else{
            header("Location: login_STUDENT.php?error=Feil epost eller passord");
            exit();
        }

        $stmt->close();
    }
} else{
    header("Location: login_STUDENT.php");
    exit();
}
?>
