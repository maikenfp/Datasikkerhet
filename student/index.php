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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!--force php to load css-->
    <link rel="stylesheet" href="./style.css?v=<?php echo time(); ?>">

<body>
    <main>
        <h1>STUDENT MELDING</h1>
        <!--Sender skjemaet til formsubmit.php-->
        <section class="mainForm">
            <form action="formsubmit.php" method="post" id="mainForm">
                <label for="subject">Emne:<span class="required"></span></label><br>
                <select name="subject" id="selectedValue" onchange="getPicture(this.value)" required>
                    <option disabled selected value>Vennligst velg et emne!</option>

                    <?php
                    $currentStudentId = $_SESSION["student_id"];
                    require '.././config/Database.php';

                    $database = new Database();
                    $db = $database->connect();

                    if (empty($currentStudentId)) {
                        header('Location: ../index.php');
                    } else {
                        $query = "SELECT * from student JOIN retning_emne re on re.retning_id = student.studieretning JOIN emne e on re.emne_id = e.emne_id WHERE student.student_id = $currentStudentId";
                        $stmt = $db->query($query);

                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($row) {
                            foreach ($row as $row) {
                                echo "<p>" . $row['emne_id'] . "</p>";
                                echo "<option value=" . $row['emne_id'] . ">" . $row['emnekode'] . ' ' . $row['emnenavn'] . "</option>";
                                $course_id = $row['emne_id'];
                                $idQuery = "SELECT bilde_navn FROM foreleser f JOIN foreleser_emne fe on fe.foreleser_id = f.foreleser_id WHERE emne_id = '$course_id'";
                            }

                            $stmt2 = $db->query($idQuery);
                            $idRow = $stmt2->fetch(PDO::FETCH_ASSOC);
                        }
                    }
                    ?>
                </select>
                <h3>Forelesere: </h3>
                <div id="foreleserDiv" class="bildewrapper">
                    <img class="foreleserImg">
                </div>
                <script>
                    const createImageElement = (pictureArray) => {
                        pictureArray.forEach(e => {
                            let img = $('<img class="foreleserImg"/>')
                            img.attr('src', ".././photos/" + e);
                            img.appendTo('#foreleserDiv');
                        });
                    }


                    function getPicture(cid) {
                        $.ajax({
                            url: "formsubmit.php", //the page containing php script
                            type: "get", //request type,
                            dataType: 'json',
                            data: {
                                "cid": cid
                            },
                            success: function(result) {
                                if (result) {
                                    $("#foreleserDiv").empty();
                                    createImageElement(result);
                                    $("#foreleserDiv").show();

                                }
                                if (!result) {
                                    console.log(result);
                                    $("#foreleserDiv").empty();
                                    $("#foreleserDiv").hide()
                                }
                            }
                        });
                    }
                </script>

                <div class="form-group">
                    <label for="subject_feedback">Tilbakemelding/Spørsmål: <span class="required">*</span></label><br>
                    <textarea name="subjectQuestion" id="subject_feedback" cols="74" rows="8" required></textarea>
                </div>
                <button type="submit" id="studentSubmit">Send inn</button>
                <a href="../logout.php">Logg ut</a>
                <a href="../bytt_passord/change_student.php">Bytt passord</a>
            </form>
        </section>
        <section class="sprsml">
            <h1>Dine tidligere spørsmål:</h1>
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