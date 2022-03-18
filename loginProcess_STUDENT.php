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
        header("Location: login_STUDENT.php?error=Du må skrive inn e-post!");
        exit();
    } else if(empty($brukerPassord)){
        header("Location: login_STUDENT.php?error=Du må skrive inn passord!");
        exit();
    } else{
		// $sql = "SELECT * FROM student WHERE epost = ? AND passord = ?";

		// $stmt = $db->prepare('SELECT * FROM student WHERE epost = :epost AND passord = :passord');
		$sql = 'SELECT * FROM student WHERE epost = :epost';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':epost', $brukerEpost, PDO::PARAM_STR);
		// $stmt->execute([$brukerEpost, $brukerPassord]);

		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if($row){
			// if(password_verify($_POST['brukerPassord'], $passord)) {

			if($brukerPassord === $row['passord']) {
				session_regenerate_id();

				$_SESSION['loggedin'] = TRUE;
				$_SESSION['navn'] = $row['navn'];
				$_SESSION['student_id'] = $row['student_id'];
				$_SESSION['studieretning'] = $row['studieretning'];
				$_SESSION['studiekull'] = $row['studiekull'];

				header("Location: student/index.php");
				exit();
			} else {
				header("Location: login_STUDENT.php?error=Feil passord  | " . $brukerEpost . " | " . $brukerPassord . " | " . $row['passord'] . " | " . $row['navn'] . " | ");
				exit();
			}
		} else{
			header("Location: login_STUDENT.php?error=Feil epost   " . $brukerEpost . $row_count);
			exit();
		}

		$stmt->close();

		/* OLD STUDENT LOGIN PROCESS

        $sql = "SELECT * FROM student WHERE epost='$brukerEpost' AND passord='$brukerPassord'";

        $stmt = $db->query($sql);
        $result = $db->prepare("SELECT SQL_CALC_FOUND_ROWS student_id, passord, studieretning, studiekull, epost, navn FROM student");
        $result->execute();
        $result = $db->prepare("SELECT FOUND_ROWS()");
        $result->execute();
        $row_count = $result->fetchColumn();

		/* OLD STUDENT LOGIN PROCESS

        if ($row_count > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['epost'] === $brukerEpost && $row['passord'] === $brukerPassord) {

                echo "Du er logget inn!";

                $_SESSION['student_id'] = $row['student_id'];
                $_SESSION['studieretning'] = $row['studieretning'];
                $_SESSION['studiekull'] = $row['studiekull'];
                $_SESSION['epost'] = $row['epost'];
                $_SESSION['navn'] = $row['navn'];

                header("Location: student/index.php");
                exit();
            } else{
                header("Location: login_STUDENT.php?error=Feil brukernavn eller passord");
                exit();
            }
        } else{
            header("Location: login_STUDENT.php?error=Feil brukernavn eller passord");
            exit();
        }
		*/
    }
} else{
    header("Location: login_STUDENT.php");
    exit();
}
?>
