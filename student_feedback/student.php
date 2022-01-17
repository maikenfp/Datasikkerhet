<!doctype html>
<html lang="nb">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8" />
    <title>Emne Tilbakemelding</title>
    <link href="stylesheet.css" rel="stylesheet" media="all" />
</head>

<body>
         <section class="maincontent">
            <main>
                <!--Sender skjemaet til formsubmit.php-->
                <form action="formsubmit.php" method="post">
                <label for="subject">Emne:<span class="required">*</span></label><br>
                    <select name="subject" required>
                    <option disabled selected value>Vennligst velg et emne!</option>
                    <?php
                    require '../db_connection.php';

                    if ($conn->error) {
                        die("Connection failed: " . $conn->error);
                    }
                    
                    $sql = "SELECT * FROM emne";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        //Henter de forskjellige emnene fra databasen
                        while ($row = $result->fetch_assoc()) {
                           echo "<option value=". $row['emnekode'] .">". $row['emnekode']. ' ' .$row['emnenavn']."</option>";                                                     
                        }
                    } else {
                        echo "0 resultater";
                    }
                    $conn->close();
                ?>
                    </select>
                    <div class="form-group">
                        <label for="subject_feedback">Tilbakemelding: <span class="required">*</span></label><br>
                        <textarea name="subject_feedback" id="subject_feedback" cols="37" rows="8" required></textarea>
                    </div>
                    <button type="submit" class="anmButtonApproved">Send inn</button>
                </form>
            </main>
        </section>
    </div>
</body>

</html>