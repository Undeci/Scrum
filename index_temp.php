
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>        
        <?php
        if (isset($_GET["toocrowded"]) && $_GET["toocrowded"] == true)
            echo 'Too crowded! <br>'
        ?>
        <form action="terrarium_temp.php" method="POST">
            dimension?<input type="number" name="dimension" value="" required><br>
            startNrPlants?<input type="number" name="startNrPlants" required><br>
            startNrCarnivore?<input type="number" name="startNrCarnivore" required><br>
            startNrHerbivore?<input type="number" name="startNrHerbivore" required><br>
            <input type="submit" value="simulate">       
        </form>         
    </body>
</html>
