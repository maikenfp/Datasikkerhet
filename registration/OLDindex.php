<?php include("../config/Database.php") ?>
<!DOCTYPE html>
<html>
    <head>
    <title>Registering av brukere</title>
    <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="header">
            <h2>Registering</h2>
        </div>
<?php


if(isset($errorMsg)){
    echo "hei";
    foreach($errorMsg as $error) {
    ?> 
    <div class?"alert alert-danger">
        <strong><?php echo $error; ?></strong>
    </div>
        <?php
    }
}
if(isset($loginMsg)) {
    ?>
    <div class="alert alert-success">
        <strong><?php echo $loginMsg; ?></strong>
    </div>
<?php
}
?>
 <form action="register.php" method="post">
        <h3>Student</h3>
        <div>
            <h4>Navn</h4>
            <input type="text" name="navn" id="navn" placeholder="Skriv inn ditt navn" >
        </div>
        <div>
        <h4>Epost</h4>
            <input type="text" name="epost" id="epost" placeholder="Skriv inn din epostadresse">
        </div>
        <div>
        <h4>Passord</h4>
            <input type="text" name="passord" id="passord" placeholder="Skriv inn ønsket passord">
        </div>
        <div>
        <h4>Studieretning</h4>
            <select name="studieretning" id="studieretning">
                <option value="informatikk">Informatikk</option>
                <option value="design">Design</option>
                <option value="litteratur">Litteratur</option>
            </select>
        </div>
        <div>
        <h4>Studiekull</h4>
            <select name="studiekull" id="studiekull">
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
            </select>
        </div>
        <div>
            <button type="submit" name="stud_reg" id="stud_reg">Register</button>
        </div>
</form>
<form action="register.php" method="post">
            <h3>Foreleser</h3>
        <div>
        <h4>Navn</h4>
            <input type="text" name="navn" id="navn" placeholder="Skriv inn ditt navn" >
        </div>
        <div>
        <h4>Epost</h4>
            <input type="text" name="epost" id="epost" placeholder="Skriv inn din epostadresse">
        </div>
        <div>
        <h4>Passord</h4>
            <input type="text" name="passord" id="passord" placeholder="Skriv inn ønsket passord">
        </div>
        <div>
        <h4>Emne</h4>
            <select name="emne_id" id="emne_id">
                <option value="1">Informatikk</option>
                <option value="2">Design</option>
                <option value="3">Litteratur</option>
            </select>
        </div>
        <div>
            <h4>Velg bilde</h4>
        <input type="file" name="picture" id="picture">
        </div>
        <div>
            <input type="submit" name="fore_reg" id="fore_reg" value="Register">
        </div>

        </form>
    </body>
</html>