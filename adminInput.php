<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        Ingelogd als admin! <br><br>
        <form action="frontcontroller.php" name="id" method="POST"><select name="id">
                <?php foreach ($lijst as $value) { ?>
                    <option value="<?php echo $value["id"] ?>">dimension:<?php echo $value['dimension'];
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . "days: " . $value["days"]; ?> </option>
                <?php }
                ?>               
            </select><input type="submit" value="Show Simulation"></form>
    </body>
</html>
