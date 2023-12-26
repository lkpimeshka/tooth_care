<?php
session_start();

require_once('classes/Appointment.php');
require_once('classes/TreatmentFactory.php');

$loggedIn = (isset($_SESSION['loggedIn'])) ? $_SESSION['loggedIn'] : false;
if (!$loggedIn) {
    header("Location: login.php");
    exit;
}

$appointmentId = isset($_GET['id']) ? $_GET['id'] : null;

$appointment = new Appointment('', '', '', '', '');
$appointmentDetails = $appointment->getAppointmentDetails($appointmentId);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $appointmentId = $_POST['appointmentId'];
    $treatmentType = $_POST['treatmentType'];

    $treatment = TreatmentFactory::createTreatment($treatmentType);
    $treatment->appointmentId = $appointmentId;

    $appointments = $appointment->getAppointmentsFromSession();
    
    foreach ($appointments as &$appntmnt) {
        if ($appntmnt['id'] === $appointmentId) {
            $appntmnt['treatment_type'] = $treatmentType;
            $appntmnt['treatment_fee'] = $treatment->getTreatmentFee();
            break;
        }
    }

    $_SESSION['appointments'] = $appointments;

    header("Location: pay_treatment_fee.php?id=" . $appointmentId);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Toothcare Hospital - Treatment Selection</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .card {
            width: 50%;
            margin: auto;
            margin-top: 50px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #3498db;
            color: #fff;
            padding: 10px;
            margin-bottom: 20px;
        }

        .header-container h1 {
            color: white;
            padding-left: 20px;
        }

        .header-container a {
            margin-right: 20px;
        }

        form {
            margin-top: 20px;
        }

        label {
            margin-bottom: 10px;
            display: block;
            font-weight: bold;
        }

        select {
            margin-bottom: 10px;
        }

        button {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <h1>Toothcare Hospital</h1>
        <div style="text-align: right;">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="card">
        <h2 class="text-center">Treatment Selection</h2>
        <hr/>
        <form method="post">
            <label>Patient Name: <?= $appointmentDetails['name'] ?></label>
            <label>Appointment ID: <?= $appointmentDetails['id'] ?></label>
            <br/>

            <label for="treatmentType">Select Treatment Type:</label>
            <select id="treatmentType" name="treatmentType" class="form-control" required>
                <option value="Cleaning">Cleaning</option>
                <option value="Whitening">Whitening</option>
                <option value="Filling">Filling</option>
                <option value="Nerve Filling">Nerve Filling</option>
                <option value="Root Canal Therapy">Root Canal Therapy</option>
            </select>

            <input type="hidden" name="appointmentId" value="<?= $appointmentDetails['id'] ?>">

            <button type="submit" name="submit" class="btn btn-primary btn-block">Save & Continue</button>
        </form>
    </div>

</body>
</html>
