<?php
require 'simulationFunctions.php';
session_start();
$_SESSION["dimension"] = $_POST["dimension"];
$_SESSION["startNrPlants"] = $_POST["startNrPlants"];
$_SESSION["startNrCarnivores"] = $_POST["startNrCarnivore"];
$_SESSION["startNrHerbivores"] = $_POST["startNrHerbivore"];

if ($_SESSION["startNrPlants"] + $_SESSION["startNrCarnivores"] + $_SESSION["startNrHerbivores"] > $_SESSION["dimension"] ** 2) {
    header('location:index_tmp.php?toocrowded=true');
    exit(0);
}
$day = 0;
do {
    if ($day == 0) {
        $simulation[$day] = generateDay0();
        $simMatrix[$day] = array_chunk($simulation[$day], $_SESSION["dimension"]);
        for ($i = 0; $i < $_SESSION["dimension"]; $i++) {
            for ($j = 0; $j < $_SESSION["dimension"]; $j++) {
                if ($simMatrix[$day][$i][$j]) {
                    $_SESSION["simData"][] = array(
                        'dimension' => $_SESSION["dimension"],
                        'day' => $day,
                        'posx' => $i,
                        'posy' => $j,
                        'type' => $simMatrix[$day][$i][$j]['type'],
                        'lifeforce' => $simMatrix[$day][$i][$j]['life'],);
                }
            }
        }
    }
    $day++;
    
    $simulation[$day] = $simulation[$day - 1];
    $simulation[$day] = eat($simulation[$day]);     
    $simulation[$day] = love($simulation[$day]);  
    $simulation[$day] = fight($simulation[$day]); 
    $simulation[$day] = move($simulation[$day]); 
    $simulation[$day] = spawnPlants($simulation[$day]); 

    $simMatrix[$day] = array_chunk($simulation[$day], $_SESSION["dimension"]);
    for ($i = 0; $i < $_SESSION["dimension"]; $i++) {
        for ($j = 0; $j < $_SESSION["dimension"]; $j++) {
            if ($simMatrix[$day][$i][$j]) {
                $_SESSION["simData"][] = array(
                    'dimension' => $_SESSION["dimension"],
                    'day' => $day,
                    'posx' => $i,
                    'posy' => $j,
                    'type' => $simMatrix[$day][$i][$j]['type'],
                    'lifeforce' => $simMatrix[$day][$i][$j]['life'],);
            }
        }
    }
} while (count($simulation[$day]) - count(array_keys($simulation[$day], null)) < $_SESSION["dimension"] ** 2)
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>       
        <?php
      
        echo '<pre>';
        print_r($simMatrix);
        print_r($_SESSION["simData"]);
        echo '</pre>'
        ?>
    </body>
</html>