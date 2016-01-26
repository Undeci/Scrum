<?php
$simulationArray = $simulation->getSimulationArray();
for ($i = 0; $i < count($simulationArray); $i++)
    $simMatrix[] = array_chunk($simulationArray[$i], $simulation->getDimension());
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>   
            body {overflow: scroll; margin-left: 5em;}
            td { width: 4em; height: 4em; text-align: center;}
            .C {background-image: url('Presentation/img/Velo.png'); background-size: contain; background-repeat: no-repeat;}
            .H {background-image: url('Presentation/img/Tric.png'); background-size: contain; background-repeat: no-repeat; }
            .P {background-image: url('Presentation/img/Sprout.png'); background-size: contain; background-repeat: no-repeat;}
            span {font-size: 3em; text-shadow: 2px 2px #ffffff; opacity: 0;}
            span:hover {opacity: 1;}
        </style>
    </head>
    <body>
            <form action="frontcontroller.php" method="GET">
            <input type="submit" value="New simulation">
            </form>
            <h1>Simulation</h1>            
            dimension: <?php echo $simulation->getDimension() ?><br>            
            number of days: <?php echo $simulation->getNumberOfDays() ?><br>
            start number herbivores: <?php echo $simulation->getStartNrHerbivore() ?><br>
            start number carnivores: <?php echo $simulation->getStartNrCarnivore() ?><br>
            start number plants: <?php echo $simulation->getStartNrPlant() ?><br>
            winner: <?php echo $simulation->getSimwinner() ?><br>

            <?php foreach ($simMatrix as $key => $day) { ?>

                <table border="1">
                    <thead><h2>Day: <?php echo $key + 1 ?></h2></thead><tbody>
                        <?php for ($i = 0; $i < $simulation->getDimension(); $i++) { ?>
                            <tr>          <?php
                            for ($j = 0; $j < $simulation->getDimension(); $j++) {
                                ?><td class="<?php echo $day[$i][$j]['type']; ?> "><span><?php echo $day[$i][$j]['life'] ?></span></td> <?php }
                            ?>
                            </tr><?php }
                    ?>
                    </tbody>
                </table>
            <?php
        }
        ?>
    </body>
</html>
