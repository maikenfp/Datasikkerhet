<?php
    include_once 'db_connection.php';

    $sql = "SELECT * from emne WHERE emne_id=88;";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    $row = mysqli_fetch_assoc($result);
    $reply = $row['reply'];

?>


<!DOCTYPE html>
<html>
    <body> 
    <h1>Foreleser</h1>

    <div>
        <h2><?php
        echo $row['emne_navn'];
        ?></h2>
    <?php
        echo "Message: ";
        echo $row['message'];
    ?>
    <form action="response_send.php" method="post">
        <?php 
        //echo "<select name=emner value=''>EMNENAVN</option>";
        //foreach ($dbo->query($sql) as $rw)
        //echo "<option value=$row[emne_navn]></option>"; 

        //echo "</select>"
        ?>

        <input type="text" name="reply" placeholder="reply">
        <button type="submit" name="submit">Respond</button>
    </form>
    </div>







    <?php
    echo "Your response is: ";
    echo "&#34;$reply&#34;";
    ?>
    </body>
</html>