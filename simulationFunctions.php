<?php
require 'debugger.php';

function hasRightNeighbour($loc) {
    if ((($loc + 1) % $_SESSION["dimension"] != 0))
        return true;
}

function rightNeighbour($simulation, $loc) {

    return $simulation[$loc + 1]['type'];
}

function generateDay0() {

    for ($i = 0; $i < $_SESSION["startNrPlants"]; $i++)
        $startSimArray[] = array('type' => 'P', 'life' => 1);
    for ($i = 0; $i < $_SESSION["startNrCarnivores"]; $i++)
        $startSimArray[] = array('type' => 'C', 'life' => 10);
    for ($i = 0; $i < $_SESSION["startNrHerbivores"]; $i++)
        $startSimArray[] = array('type' => 'H', 'life' => 10);
    for ($i = count($startSimArray); $i < $_SESSION["dimension"] ** 2; $i++)
        $startSimArray[] = null;
    
    shuffle($startSimArray);

    return $startSimArray;
}

function spawnPlants($simulation) {

    $SPAWNPROBABILITY = 25; // percentage

    $emptyFields = array_keys($simulation, null);
    $emptyFields = array_fill_keys($emptyFields, null);

    for ($i = 0; $i < count($simulation); $i++)
        if (array_key_exists($i, $emptyFields) && mt_rand(0, 99) < $SPAWNPROBABILITY) {
            $emptyFields[$i]['type'] = 'P';
            $emptyFields[$i]['life'] = 1;
        }
    $simulationPlantsSpawned = array_replace($simulation, $emptyFields);

    return $simulationPlantsSpawned;
}

function eat($simulation) {
    //eat herbivores
    $Hlocation = array();
    $Clocation = array();
    foreach ($simulation as $location => $key) {
        if ($key['type'] == 'H')
            $Hlocation[] = $location;

        if ($key['type'] == 'C')
            $Clocation[] = $location;
    }

    foreach ($Hlocation as $herbivore) {
        if (hasRightNeighbour($herbivore) && rightNeighbour($simulation, $herbivore) == "P") {
            $simulation[$herbivore + 1] = null;
            $simulation[$herbivore]['life'] ++;
        }
    }
    //eat carnivores
    foreach ($Clocation as $carnivore) {
        if (hasRightNeighbour($carnivore) && rightNeighbour($simulation, $carnivore) == "H") {
            $simulation[$carnivore]['life'] += $simulation[$carnivore + 1]['life'];
            $simulation[$carnivore + 1] = null;
        }
    }

    return $simulation;
}

function love($simulation) {

    $Hlocation = array();
    $Elocation = array();
    foreach ($simulation as $location => $key) {

        if ($key['type'] == 'H')
            $Hlocation[] = $location;
        if ($key == null)
            $Elocation[] = $location;
    }
    foreach ($Hlocation as $herbivore) {
        if (hasRightNeighbour($herbivore) && rightNeighbour($simulation, $herbivore) == "H") {
            if (count($Elocation) > 0) {
                $random = array_rand($Elocation, 1);
                $simulation[$random]['type'] = 'H';
                $simulation[$random]['life'] = 10;
                unset($Elocation[$random]);
            }
        }
    }
    return $simulation;
}

function fight($simulation) {
    $Clocation = array();

    foreach ($simulation as $location => $key) {

        if ($key['type'] == 'C')
            $Clocation[] = $location;
    }

    foreach ($Clocation as $carnivore) {
        if (hasRightNeighbour($carnivore) && rightNeighbour($simulation, $carnivore) == "C" && $simulation[$carnivore]['life'] != $simulation[$carnivore + 1]['life']) {
            if ($simulation[$carnivore]['life'] > $simulation[$carnivore + 1]['life']) {
                $simulation[$carnivore]['life'] += $simulation[$carnivore + 1]['life'];
                $simulation[$carnivore + 1] = null;
            } else {
                $simulation[$carnivore + 1]['life'] += $simulation[$carnivore]['life'];
                $simulation[$carnivore] = null;
            }
        }
    }
    return $simulation;
}

function move($simulation) {
    
//    $Hlocation = array();
//    $Clocation = array();
//    foreach ($simulation as $location => $key) {
//        if ($key['type'] == 'H')
//            $Hlocation[] = $location;
//
//        if ($key['type'] == 'C')
//            $Clocation[] = $location;
//        if ($key == null)
//            $Elocation[] = $location;
//    }
//    
//    foreach ($Clocation as $carnivore) {
//        if (hasRightNeighbour($carnivore) && rightNeighbour($simulation, $carnivore) == null) {
//            $moveCandidates = array($carnivore - $_SESSION["dimension"], $carnivore + $_SESSION["dimension"],$carnivore - 1, $carnivore + 1);
//            
//            $moveloc = array_rand($moveCandidates, 1);
//            if (in_array($moveloc, $Elocation)) {
//                    $simulation[$moveloc] = $simulation[$carnivore]; 
//                    $simulation[$carnivore] = null;
//                    
//            }
//        }
//    }
//    foreach ($Hlocation as $herbivore) {
//        if (hasRightNeighbour($herbivore) && rightNeighbour($simulation, $herbivore) == null) {
//            
//        }
//        
//    }
    
    return $simulation;
}
