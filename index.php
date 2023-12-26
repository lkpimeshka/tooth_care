<?php

session_start();

require_once('classes/Appointment.php');
require_once('classes/Treatment.php');
require_once('classes/TreatmentFactory.php');

$appointments = isset($_SESSION['appointments']) ? $_SESSION['appointments'] : [];

$loggedIn = (isset($_SESSION['loggedIn'])) ? $_SESSION['loggedIn'] : false;

if (!$loggedIn) {
    header("Location: login.php");
    exit;
}

$appointmentObject = new Appointment('', '', '', '', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filterByDate'])) {

    $filterDate = $_POST['filter_date'];
    $_SESSION['appointment_filter_date'] = $filterDate;

    $appointmentList = $appointmentObject->getAppointmentsFromSession();
    $appointments = [];
        foreach ($appointmentList as $appointment) {
            if (isset($appointment['date']) && date('Y-m-d', strtotime($appointment['date'])) === $filterDate) {
                $appointments[] = $appointment;
            }
        }

}elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchById'])) {

    $searchId = isset($_POST['appointment_id']) ? $_POST['appointment_id'] : '';
    
    $appointmentList = $appointmentObject->getAppointmentsFromSession();
    $appointments = [];
        foreach ($appointmentList as $appointment) {
            if (isset($appointment['id']) && $appointment['id'] == $searchId) {
                $appointments[] = $appointment;
            }
        }

}else{
    $appointments = $appointmentObject->getAppointmentsFromSession();
}

unset($appointmentObject);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Toothcare Hospital - Appointment List</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
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

        h2 {
            color: #4caf50;
        }

        .form-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        form {
            display: flex;
            align-items: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        td:last-child {
            text-align: right;
        }

        button {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            margin-left: 10px;
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

    <div style="padding-left: 40px; padding-right: 40px;">

        <div class="row">
            <div class="col-sm-6">
                <h2>Appointment List</h2>
            </div>
            <div class="col-sm-6">
                <div style="text-align: right;">
                    <a href="create_appointment.php" class="btn btn-success">New Appointment</a>
                </div>
            </div>
        </div>  
        <hr style="margin-bottom: 50px">
        <div class="row">
            <div class="col-sm-5">
                <form method="POST" action="">
                    <label for="appointment_id" style="width: 50%">Search by Appointment ID:</label>
                    <input class="form-control" type="text" style="margin-left: 10px;" id="appointment_id" name="appointment_id" required>
                    <button type="submit" name="searchById" class="btn btn-primary">Search</button>
                </form>
            </div>
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <form method="POST" action="">
                    <label for="filter_date" style="width: 50%">Filter by Date:</label>
                    <input type="date" class="form-control" id="filter_date" name="filter_date" value="" required>
                    <button type="submit" name="filterByDate" class="btn btn-primary">Filter</button>
                </form>
            </div>
        </div>

        <table border='1'>
            <tr>
                <th>Appointment ID</th>
                <th>Patient Name</th>
                <th>Telephone</th>
                <th>Status</th>
                <th>Appointment Date</th>
                <th></th>
            </tr>

            <?php if (!empty($appointments)) : ?>
                <?php foreach ($appointments as $appointment) : ?>
                    <tr>
                        <td><?= $appointment['id'] ?></td>
                        <td><?= $appointment['name'] ?></td>
                        <td><?= $appointment['telephone'] ?></td>
                        <td>
                            <?php 
                                if($appointment['status'] == 1){
                                    echo 'Pending';
                                } elseif($appointment['status'] == 2){
                                    echo 'Confirmed';
                                } else {
                                    echo 'Completed';
                                }
                            ?>
                        </td>
                        <td><?= $appointment['date'].', '.$appointment['time'] ?></td>
                        <td>
                            <?php if ($appointment['status'] == 1) : ?>
                                <a href="edit_appointment.php?id=<?= $appointment['id'] ?>" class="btn btn-primary">Edit</a>
                                <a href="pay_registration.php?id=<?= $appointment['id'] ?>" class="btn btn-primary">Pay Registration Fee</a>
                            <?php endif; ?>

                            <?php if ($appointment['status'] == 2) : ?>
                                <a href="treatment.php?id=<?= $appointment['id'] ?>" class="btn btn-primary">Pay Treatment Fee</a>
                            <?php endif; ?>

                            <?php if ($appointment['status'] == 3) : ?>
                                <a href="invoice.php?id=<?= $appointment['id'] ?>" class="btn btn-primary">View Invoice</a>
                            <?php endif; ?>
                        </td>
                        
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" style="text-align: center">No appointments available.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>


