<?php
// Start the session
session_start();
?>
<!doctype html>
<html lang="nb">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8" />
    <title>Student Melding</title>
    <!--force php to load css-->
    <link rel="stylesheet" href="./style.css?v=<?php echo time(); ?>">

<body>
    <main>
        <h1>STUDENT MELDING</h1>
        <!--Sender skjemaet til formsubmit.php-->
        <section class="mainForm">
            <form action="formsubmit.php" method="post">
                <label for="subject">Emne:<span class="required">*</span></label><br>
                <select name="subject" required>
                    <option disabled selected value>Vennligst velg et emne!</option>
                    <!--TODO: Få session for den innologgende studenten. Hente tidligere sendte meldinginger med student sessionen hvor man har svar-->
                    <?php
                    $currentStudentId = $_SESSION["student_id"];
                    require '.././config/Database.php';

                    $database = new Database();
                    $db = $database->connect();

                    if (empty($currentStudentId)) {
                        header('Location: ../index.php');
                    } else {
                        $query = "SELECT e.emnenavn, e.emne_id, e.emnekode from student inner JOIN student_emne se on se.student_id = student.student_id inner join emne e on se.emne_id = e.emne_id WHERE se.student_id = $currentStudentId ";

                        $stmt = $db->query($query);

                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($row) {
                            foreach ($row as $row) {
                                echo "<option value=" . $row['e.emne_id'] . ">" . $row['e.emnekode'] . ' ' . $row['e.emnenavn'] . "</option>";
                            }
                        }
                    }
                    ?>
                </select>
                <div class="form-group">
                    <label for="subject_feedback">Tilbakemelding/Spørsmål: <span class="required">*</span></label><br>
                    <textarea name="subjectQuestion" id="subject_feedback" cols="74" rows="8" required></textarea>
                </div>
                <button type="submit" id="studentSubmit">Send inn</button>
                <a href="../logout.php">Logg ut</a>
            </form>
        </section>
        <h1>Dine tidligere spørsmål:</h1>
        <section class="sprsml">
            <div class="question">
                <?php
                //Midlertidig løsning for å hente spørsmål
                $query = "SELECT * from melding WHERE student_id = $currentStudentId";

                $stmt = $db->query($query);

                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($row) {
                    foreach ($row as $row) {
                        echo "<article>
                                <h1 value=" . $row['melding_id'] . ">" . $row['spørsmål'] . "</h1><p>" . $row['dato'] . " <br> " . $row['tid'] . "</p>
                                <h1>Svar fra foreleser</h1><p>" . $row['svar'] . "</p></article>";
                        //</h1><h1>Kommentarer fra andre studenter:</h1><p>". $row['kommentar'] ."</p></article>";
                    }
                }
                ?>
            </div>
        </section>
    </main>
</body>

</html>