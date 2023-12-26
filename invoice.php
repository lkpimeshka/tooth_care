<?php
session_start();

require_once('classes/Appointment.php');

$loggedIn = (isset($_SESSION['loggedIn'])) ? $_SESSION['loggedIn'] : false;
if (!$loggedIn) {
    header("Location: login.php");
    exit;
}

$appointmentId = isset($_GET['id']) ? $_GET['id'] : null;

$appointment = new Appointment('', '', '', '', '');
$appointmentDetails = $appointment->getAppointmentDetails($appointmentId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Toothcare Hospital - Invoice</title>
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
            margin-bottom: 5px;
            display: block;
            font-weight: bold;
        }

        p {
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

        .btn-success {
            background-color: #2ecc71;
            color: #fff;
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

    <div class="row">
        <div class="col-sm-12">
            <div style="text-align: center;">
                <a href="index.php" class="btn btn-success">View Appointment List</a>
            </div>
        </div>
    </div>  

    <div class="card">
        <h1 class="text-center">Invoice</h1>
        <hr/>
        <p><strong>Appointment ID:</strong> <?= $appointmentDetails['id'] ?></p>
        <p><strong>Patient Name:</strong> <?= $appointmentDetails['name'] ?></p>
        <p><strong>Address:</strong> <?= $appointmentDetails['address'] ?></p>
        <p><strong>Telephone:</strong> <?= $appointmentDetails['telephone'] ?></p>
        <p><strong>Appointment Date/Time:</strong> <?= $appointmentDetails['date'].' / '.$appointmentDetails['time'] ?></p>
        <p><strong>Registration Fee:</strong> <?= 'Rs. '.number_format($appointmentDetails['reg_fee'], 2) ?></p>
        <p><strong>Registration Payment Ref No:</strong> <?= $appointmentDetails['reg_ref_no'] ?></p>
        <p><strong>Treatment Type:</strong> <?= $appointmentDetails['treatment_type'] ?></p>
        <p><strong>Treatment Fee:</strong> <?= 'Rs. '.number_format($appointmentDetails['treatment_fee'], 2) ?></p>
        <p><strong>Treatment Payment Ref No:</strong> <?= $appointmentDetails['treatment_ref_no'] ?></p>
    </div>
</body>
</html>

