<?php
require_once 'simulationFunctions.php';
require_once 'Simulation.php';
require_once 'simulationDAO.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header('location: userInput.php');
    exit();
}
if (isset($_POST["dimension"])) {
    if ($_POST["startNrPlant"] + $_POST["startNrCarnivore"] + $_POST["startNrHerbivore"] > $_POST["dimension"] ** 2) {
        header('location: userInput.php?toocrowded=true');
        exit();
    }
    $simulation = new Simulation($_POST["dimension"], $_POST["startNrHerbivore"], $_POST["startNrCarnivore"], $_POST["startNrPlant"]);
    include 'simulationPresentation.php';
    exit();
}
if (isset($_POST["admin"])) {
    $dao = new SimulationDAO();
    $lijst = $dao->getSimulationCharacteristics();
    include 'adminInput.php';
    exit();
}
if (isset($_POST["id"])) {
    $dao = new SimulationDAO();
    $simulation = $dao->getSimulationById($_POST["id"]);
    include 'simulationPresentation.php';
    exit();
}