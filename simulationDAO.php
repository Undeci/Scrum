<?php
class SimulationDAO {

     public function store($simulation) {
        
        $dimension = $simulation->getDimension();
        $days = $simulation->getNumberOfDays();
        $P = $simulation->getStartNrPlant();
        $H = $simulation->getStartNrHerbivore();
        $C = $simulation->getStartNrCarnivore();
        $winner = $simulation->getSimwinner();
        
        $simArraySer = serialize($simulation->getSimulationArray());
         
        $sql = "insert into terrariumsimulations (simulation, dimension, days, startplant, startherbivore, startcarnivore, winner)  
		values (:simArraySer, :dimension, :days, :P, :H, :C, :winner)";
        $dbh = new PDO("mysql:host=localhost;dbname=terrarium;charset=utf8", "root", "undeci");
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':simArraySer' => $simArraySer, ':dimension' => $dimension, ':days' => $days, ':P' => $P, ':H' => $H, ':C' => $C, ':winner' => $winner));
        $dbh = null;
    }
    public function getSimulationById($id) {
        
        $sql = "select simulation, dimension, days, startplant, startherbivore, startcarnivore, winner from terrariumsimulations where id = $id";        
        $dbh = new PDO("mysql:host=localhost;dbname=terrarium;charset=utf8", "root", "");
        $stmt = $dbh->prepare($sql);        
	$stmt->execute();        
        $resultset = $stmt->fetch(PDO::FETCH_NUM);        
        $resultset = array_replace($resultset, array(unserialize($resultset[0])));
                
        $simulation = new Simulation(...$resultset);
        $dbh = null;

        return $simulation;
    }
    public function getSimulationCharacteristics() {
        $sql = "select id, dimension, days, startplant, startherbivore, startcarnivore, winner from terrariumsimulations"; 
        $dbh = new PDO("mysql:host=localhost;dbname=terrarium;charset=utf8", "root", "undeci");
        $stmt = $dbh->prepare($sql);        
	$stmt->execute();  
        $resultset = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $lijst = array();
        foreach ($resultset as $rij) {
            $lijst[] = $rij;
        }       
        $dbh = null;
        
        return $lijst;        
    }   
}
