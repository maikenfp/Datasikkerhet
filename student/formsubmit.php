<?php 
// Start the session
session_start();
require '.././config/Database.php';

$database = new Database();
$db = $database->connect();


//se hva som har blitt sendt dra skjemaet: var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentStudentId = 1;

    $subject= $_POST['subject'];
    $question = $_POST['subjectQuestion'];

    $date = date('Y-m-d');

    $sql = "INSERT INTO melding(spørsmål, emne_id, student_id, dato, tid, foreleser_id) 
    VALUES ('$question', '$subject', '$currentStudentId', '$date', (NOW()), (SELECT foreleser_id FROM foreleser_emne WHERE emne_id = $subject))";
    
    $result = $db->query($sql);

    if($result) {
        echo "<script>";
        echo "alert('Tilbakemeldingen din er mottatt!');";
        echo "</script>";
        echo "<meta http-equiv='refresh' content='0;url=index.php'>";
    }

}
?>
 <!-- INSERT INTO melding(spørsmål, emne_id, student_id, foreleser_id)
	VALUES('question', 1, 1,
           (SELECT melding.foreleser_id
    		FROM foreleser_emne AS FE
    		JOIN melding AS M ON FE.emne_id = M.emne_id 
            	WHERE FE.emne_id = 1)) -->