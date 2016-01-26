
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>        
        <?php
        if (isset($_GET["toocrowded"]) && $_GET["toocrowded"] == true)
            echo 'Too crowded! <br><br>'
            ?>
        <form action="frontcontroller.php" method="POST">
            dimension?<input type="number" name="dimension" min="2" required><br>
            startNrPlants?<input type="number" name="startNrPlant" min="0" required><br>
            startNrCarnivore?<input type="number" name="startNrCarnivore" min="0" required><br>
            startNrHerbivore?<input type="number" name="startNrHerbivore" min="0" required><br>
            <input type="submit" value="simulate" name="simulate">
        </form>
        <form action="frontcontroller.php" method="POST" name="admin">
            <input type="submit" value="log in als admin" name="admin">
        </form>
    </body>
</html>
