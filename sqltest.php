<?php

        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * from foreleser WHERE foreleser_id='1'"
        $stmt = $db->query($sql);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if ($row) {

            foreach ($row as $row) {
                echo "<div class='respond-form'>";
                echo "<br>Dato:" . $row["epost"] . "$row['passord']"
                $meldingID = $row['melding_id'];
        ?>
