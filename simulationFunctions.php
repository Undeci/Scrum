<?php

function hasRightNeighbour($loc) {
    if ((($loc + 1) % $_SESSION["dimension"] != 0))
        return true;
}

function rightNeighbour($simulation, $loc) {

    return $simulation[$loc + 1]['type'];
}

function generateDay0() {
    $startSimArray = array();
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

    foreach ($simulation as $loc => $value) {
        if ($value == null && mt_rand(0, 99) < $SPAWNPROBABILITY) {
            $simulation[$loc]['type'] = 'P';
            $simulation[$loc]['life'] = 1;
        }
    }
    return $simulation;
}

function spawnOnePlant($simulation) {
    $Elocation = array();
    foreach ($simulation as $loc => $value) {
        if ($value == null)
            $Elocation[] = $loc;
    }
    if (count($Elocation) > 0) {
        $random = array_rand($Elocation, 1);
        $simulation[$Elocation[$random]]['type'] = 'P';
        $simulation[$Elocation[$random]]['life'] = 1;
    }
    return $simulation;
}

function eat($simulation) {

    $Hlocation = array();
    $Clocation = array();
    foreach ($simulation as $location => $value) {
        if ($value['type'] == 'H')
            $Hlocation[] = $location;
        if ($value['type'] == 'C')
            $Clocation[] = $location;
    }
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

    $Hlocation = array();
    $Elocation = array();
    foreach ($simulation as $location => $value) {

        if ($value['type'] == 'H')
            $Hlocation[] = $location;
        if ($value == null)
            $Elocation[] = $location;
    }
    foreach ($Hlocation as $herbivore) {
        if (hasRightNeighbour($herbivore) && rightNeighbour($simulation, $herbivore) == "H" && !$_SESSION["action"][$herbivore]) {
            if (count($Elocation) > 0) {
                $_SESSION["action"][$herbivore] = true;
//                $_SESSION["action"][$herbivore + 1] = true;     //action voor paar slachtoffer
                $random = array_rand($Elocation, 1);
                $randomEmpty = $Elocation[$random];
                $simulation[$randomEmpty]['type'] = 'H';
                $simulation[$randomEmpty]['life'] = 10;
//                $_SESSION["action"][$randomEmpty] = true;      //baby herbivoor niet paren
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

    $Hlocation = array();
    $Clocation = array();
    $Elocation = array();
    foreach ($simulation as $location => $key) {
        if ($key['type'] == 'H')
            $Hlocation[] = $location;
        if ($key['type'] == 'C')
            $Clocation[] = $location;
        if ($key == null)
            $Elocation[] = $location;
    }
    foreach ($Clocation as $carnivore) {
        if (hasRightNeighbour($carnivore) && rightNeighbour($simulation, $carnivore) == null && !$_SESSION["action"][$carnivore]) {
            $moveCandidates = array($carnivore - $_SESSION["dimension"], $carnivore + $_SESSION["dimension"], $carnivore - 1, $carnivore + 1);
            do {
                $moveloc = array_rand($moveCandidates, 1);
                $moveloc = $moveCandidates[$moveloc];
            } while (!in_array($moveloc, $Elocation));

            $simulation[$moveloc] = $simulation[$carnivore];
            $_SESSION["action"][$moveloc] = true;
            $simulation[$carnivore] = null;
        }
    }
    foreach ($Hlocation as $herbivore) {
        if (hasRightNeighbour($herbivore) && rightNeighbour($simulation, $herbivore) == null && !$_SESSION["action"][$herbivore]) {
            $moveCandidates = array($herbivore - $_SESSION["dimension"], $herbivore + $_SESSION["dimension"], $herbivore - 1, $herbivore + 1);
            do {
                $moveloc = array_rand($moveCandidates, 1);
                $moveloc = $moveCandidates[$moveloc];
            } while (!in_array($moveloc, $Elocation));

            $simulation[$moveloc] = $simulation[$herbivore];
            $_SESSION["action"][$moveloc] = true;
            $simulation[$herbivore] = null;
        }
    }
    unset($_SESSION["action"]);

    return $simulation;
}
