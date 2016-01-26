<?php
class Simulation {

    private $simulationArray;
    private $dimension;
    private $startNrHerbivore;
    private $startNrCarnivore;
    private $startNrPlant;
    private $simwinner;
    private $numberOfDays;

    public function __construct() {

        if (func_num_args() == 4)
            call_user_func_array(array($this, "constructFromUser"), func_get_args());
        if (func_num_args() == 7)
            call_user_func_array(array($this, "constructFromDb"), func_get_args());
    }

    public function constructFromDb($simArray, $dimension, $days, $startP, $startH, $startC, $winner) {
        $this->simulationArray = $simArray;
        $this->dimension = $dimension;
        $this->startNrHerbivore = $startH;
        $this->startNrCarnivore = $startC;
        $this->startNrPlant = $startP;
        $this->simwinner = $winner;
        $this->numberOfDays = $days;
    }

    public function constructFromUser($dimension, $startNrH, $startNrC, $startNrP) {
        $this->dimension = $dimension;
        $this->startNrCarnivore = $startNrC;
        $this->startNrHerbivore = $startNrH;
        $this->startNrPlant = $startNrP;
        $this->simulationArray = generateSimulationArray($dimension, $startNrC, $startNrH, $startNrP);
        $this->numberOfDays = count($this->simulationArray);
        $this->simwinner = checkSimWinner($this->simulationArray[($this->numberOfDays) - 1]);

        $dao = new SimulationDAO();
        $dao->store($this);
    }

    public function getSimulationArray() {
        return $this->simulationArray;
    }

    public function getDimension() {
        return $this->dimension;
    }

    public function getStartNrHerbivore() {
        return $this->startNrHerbivore;
    }

    public function getStartNrCarnivore() {
        return $this->startNrCarnivore;
    }

    public function getStartNrPlant() {
        return $this->startNrPlant;
    }

    public function getSimwinner() {
        return $this->simwinner;
    }

    public function getNumberOfDays() {
        return $this->numberOfDays;
    }
}
