<?php
session_start();

require_once('classes/Appointment.php');
require_once('classes/Payment.php');

$loggedIn = (isset($_SESSION['loggedIn'])) ? $_SESSION['loggedIn'] : false;
if (!$loggedIn) {
    header("Location: login.php");
    exit;
}

$appointmentId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$appointmentId) {
    header("Location: index.php");
    exit;
}

$appointment = new Appointment('', '', '', '', '');
$appointmentDetails = $appointment->getAppointmentDetails($appointmentId);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrationFee'])) {

    $paymentRef = $_POST['paymentRef'];

    $registrationFee = new RegistrationFee(uniqid('rf_'), $paymentRef, $appointmentDetails['id'], '');
    $appointments = $appointment->getAppointmentsFromSession();
        foreach ($appointments as &$appntmnt) {
            if ($appntmnt['id'] === $appointmentDetails['id']) {
                $appntmnt['status'] = 2;
                $appntmnt['reg_fee'] = $registrationFee->getRegistrationFee();
                $appntmnt['reg_ref_no'] = $registrationFee->getPaymentRefNo();
                break;
            }
        }
    $_SESSION['appointments'] = $appointments;

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Toothcare Hospital - Pay Registration</title>
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
            margin-right: 10px;
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
        <h2 class="text-center">Registration Fee | Rs. 1000.00</h2>
        <hr/>

        <form method="post">
            <label for="paymentRef">Payment Ref No:</label>
            <input type="text" id="paymentRef" name="paymentRef" class="form-control" required>
            <br>

            <button type="submit" name="registrationFee" class="btn btn-primary btn-block">Pay Now</button>
        </form>
    </div>

</body>
</html>