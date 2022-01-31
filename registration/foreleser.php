<!DOCTYPE html>
<html>
    <head>
    <title>Registering</title>
    <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <div class="header">
            <h2>Registering</h2>
        </div>
<?php include("../config/Database.php")?>

<form action="register.php" method="post">
            <h3>Foreleser</h3>
            <?php if (isset($_GET['error'])) { ?>
	            <p class="error"><?php echo $_GET['error']; ?></p>
	        <?php } ?>
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