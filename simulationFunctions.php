<?php

function generateSimulationArray($dimension, $startNrC, $startNrH, $startNrP) {
    
    $_SESSION["dimension"] = $dimension;    
    $simulationArray = array();    
    $simulationday = generateDay0($startNrC, $startNrH, $startNrP);
    $simulationArray[] = $simulationday;
    
    do {
        
    $simulationeaten = eat($simulationday);
    $simulationloved = love($simulationeaten);
    $simulationfaught = fight($simulationloved);
    $simulationmoved = move($simulationfaught);
    $simulationspawned = spawnOnePlant($simulationmoved);    
    $simulationday = $simulationspawned;
    $simulationArray[] = $simulationday;
    
    } while ( count($simulationday) - count(array_keys($simulationday, null)) < $dimension ** 2 ) ;
    
    return $simulationArray;
}
function hasRightNeighbour($loc) {
    if (($loc + 1) % $_SESSION["dimension"] != 0)
        return true;
}

function rightNeighbour($simulation, $loc) {

    return $simulation[$loc + 1]['type'];
}

function OrganismLocations($simulation, $organism) {
    
    $OrganismLocations = array();    
    foreach ($simulation as $location => $value) {
        if ($value['type'] == $organism)
            $OrganismLocations[] = $location;  
    }
    return $OrganismLocations;
}

function generateDay0($startNrC, $startNrH, $startNrP) {
    $startSimArray = array();
    for ($i = 0; $i < $startNrP; $i++)
        $startSimArray[] = array('type' => 'P', 'life' => 1);
    for ($i = 0; $i < $startNrC; $i++)
        $startSimArray[] = array('type' => 'C', 'life' => 10);
    for ($i = 0; $i < $startNrH; $i++)
        $startSimArray[] = array('type' => 'H', 'life' => 10);
    for ($i = count($startSimArray); $i < $_SESSION["dimension"] ** 2; $i++)
        $startSimArray[] = null;

    shuffle($startSimArray);

    return $startSimArray;
}

function spawnPlants($simulation) {

    $SPAWNPROBABILITY = 25; // percentage

    foreach ($simulation as $loc => $value) {
        if ($value == null && mt_rand(0, 99) < $SPAWNPROBABILITY) {
            $simulation[$loc]['type'] = 'P';
            $simulation[$loc]['life'] = 1;
        }
    }
    return $simulation;
}

function spawnOnePlant($simulation) {
    
    $Elocation = OrganismLocations($simulation, null);
    if (count($Elocation) > 0) {
        $random = array_rand($Elocation, 1);
        $simulation[$Elocation[$random]]['type'] = 'P';
        $simulation[$Elocation[$random]]['life'] = 1;
    }
    return $simulation;
}

function eat($simulation) {
    
    for ($i = 0; $i < $_SESSION["dimension"] ** 2; $i++)
        $_SESSION["action"][] = false;

    $Hlocation = OrganismLocations($simulation, 'H');
    $Clocation = OrganismLocations($simulation, 'C');

    foreach ($Hlocation as $herbivore) {
        if (hasRightNeighbour($herbivore) && rightNeighbour($simulation, $herbivore) == "P" && !$_SESSION["action"][$herbivore]) {
            $simulation[$herbivore + 1] = null;
            $simulation[$herbivore]['life'] ++;
            $_SESSION["action"][$herbivore] = true;
        }
    }
    foreach ($Clocation as $carnivore) {
        if (hasRightNeighbour($carnivore) && rightNeighbour($simulation, $carnivore) == "H" && !$_SESSION["action"][$carnivore]) {
            $simulation[$carnivore]['life'] += $simulation[$carnivore + 1]['life'];
            $simulation[$carnivore + 1] = null;
            $_SESSION["action"][$carnivore] = true;
        }
    }

    return $simulation;
}

function love($simulation) {

    $Hlocation = OrganismLocations($simulation, 'H');
    $Elocation = OrganismLocations($simulation, '');
    foreach ($Hlocation as $herbivore) {
        if (hasRightNeighbour($herbivore) && rightNeighbour($simulation, $herbivore) == "H" && !$_SESSION["action"][$herbivore]) {
            if (count($Elocation) > 0) {
                $_SESSION["action"][$herbivore] = true;
//                $_SESSION["action"][$herbivore + 1] = true;     //action voor paar slachtoffer
                $random = array_rand($Elocation, 1);
                $randomEmpty = $Elocation[$random];
                $simulation[$randomEmpty]['type'] = 'H';
                $simulation[$randomEmpty]['life'] = 10;
                $_SESSION["action"][$randomEmpty] = true;      //baby herbivoor niet paren                
                $Elocation = array_diff($Elocation, array($randomEmpty));
            }
        }
    }
    return $simulation;
}

function fight($simulation) {
    $Clocation = OrganismLocations($simulation, 'C');
    foreach ($Clocation as $carnivore) {
        if (hasRightNeighbour($carnivore) && rightNeighbour($simulation, $carnivore) == "C" && $simulation[$carnivore]['life'] != $simulation[$carnivore + 1]['life'] && !$_SESSION["action"][$carnivore]) {
            if ($simulation[$carnivore]['life'] > $simulation[$carnivore + 1]['life']) {
                $simulation[$carnivore]['life'] += $simulation[$carnivore + 1]['life'];
                $simulation[$carnivore + 1] = null;
                $_SESSION["action"][$carnivore] = true;
            } else {
                $simulation[$carnivore + 1]['life'] += $simulation[$carnivore]['life'];
                $simulation[$carnivore] = null;
                $_SESSION["action"][$carnivore + 1] = true;
            }
        }
    }
    return $simulation;
}

function move($simulation) {

    $Clocation = OrganismLocations($simulation, 'C');
    $Elocation = OrganismLocations($simulation, '');
    foreach ($Clocation as $carnivore) {
        if (hasRightNeighbour($carnivore) && rightNeighbour($simulation, $carnivore) == null && !$_SESSION["action"][$carnivore]) {

            $moveCandidates = array($carnivore - $_SESSION["dimension"], $carnivore + $_SESSION["dimension"], $carnivore + 1);
            if ($carnivore % $_SESSION["dimension"] != 0)
                $moveCandidates[] = $carnivore -1;
            
            do {
                $moveloc = array_rand($moveCandidates, 1);
                $moveloc = $moveCandidates[$moveloc];
            } while (!in_array($moveloc, $Elocation));
            $simulation[$moveloc] = $simulation[$carnivore];
            $Clocation[$carnivore] = $moveloc;
            $_SESSION["action"][$moveloc] = true;
            $simulation[$carnivore] = null;
            $Elocation[] = $carnivore;
            $Elocation = array_diff($Elocation, array($moveloc));
        }
    }
    $Elocation = OrganismLocations($simulation, '');
    $Hlocation = OrganismLocations($simulation, 'H');
    foreach ($Hlocation as $herbivore) {
        if (hasRightNeighbour($herbivore) && rightNeighbour($simulation, $herbivore) == null && !$_SESSION["action"][$herbivore]) {

            $moveCandidates = array($herbivore - $_SESSION["dimension"], $herbivore + $_SESSION["dimension"], $herbivore + 1);
            if ($herbivore % $_SESSION["dimension"] != 0)
                $moveCandidates[] = $herbivore -1;
            do {
                $moveloc = array_rand($moveCandidates, 1);
                $moveloc = $moveCandidates[$moveloc];
            } while (!in_array($moveloc, $Elocation));

            $simulation[$moveloc] = $simulation[$herbivore];
            $Hlocation[$herbivore] = $moveloc;
            $_SESSION["action"][$moveloc] = true;
            $simulation[$herbivore] = null;             
            $Elocation[] = $herbivore;
            $Elocation = array_diff($Elocation, array($moveloc));
        }
    }
    unset($_SESSION["action"]);

    return $simulation;
}
function checkSimWinner($simulation) {
            
        $countH = count(OrganismLocations($simulation, 'H'));
        $countC = count(OrganismLocations($simulation, 'C'));
        $countP = count(OrganismLocations($simulation, 'P'));
        
        $maxcount = max($countC, $countH, $countP);
        
        if ($countC == $maxcount)
            $simWinner = 'carnivore';
        if ($countH == $maxcount)
            $simWinner = 'herbivore';
        if ($countP == $maxcount)
            $simWinner = 'plant';
  
        return $simWinner;    
}
