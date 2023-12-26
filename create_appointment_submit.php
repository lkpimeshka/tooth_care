<?php

session_start();

require_once('classes/User.php');
require_once('classes/Appointment.php');

$loggedIn = (isset($_SESSION['loggedIn'])) ? $_SESSION['loggedIn'] : false;

if (!$loggedIn) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookAppointment'])) {
    $patientName = $_POST['patientName'];
    $address = $_POST['address'];
    $telephone = $_POST['telephone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $status = 1;

    $patient = new Patient(uniqid('usr_'), $patientName, $telephone, $address);
    $appointment = new Appointment(uniqid('app_'), $date, $time, $status, $patient->getId());

    $appointments = isset($_SESSION['appointments']) ? $_SESSION['appointments'] : [];
    $appointments[] = [
        'id' => $appointment->getAppointmentId(),
        'patient_id' => $appointment->getPatientId(),
        'name' => $patient->getName(),
        'address' => $patient->getAddress(),
        'telephone' => $patient->getTelephone(),
        'date' => $appointment->getDate(),
        'time' => $appointment->getTime(),
        'status' => $status,
        'reg_fee' => null,
        'reg_ref_no' => null,
        'treatment_type' => null,
        'treatment_fee' => null,
        'treatment_ref_no' => null,
        
    ];

    $_SESSION['appointments'] = $appointments;

    unset($patient);
    unset($appointment);

    header("Location: index.php");
    exit;
}



?>

