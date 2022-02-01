<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body> 

    <h1>Bytt passord</h1>
        <form action='switch.php' method="POST">

        <?php if (isset($_GET['error'])) { ?>
	            <p class="error"><?php echo $_GET['error']; ?></p>
	    <?php } ?>

        <label>Passord</label>
            <input type='text' name="passord">
        <label>Nytt passord</label>
            <input type='text' name="nyttpassord">
            <input type="submit" name="submit">
        </form>
    </body>
</html>