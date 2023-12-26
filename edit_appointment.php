<?php
session_start();

require_once('classes/User.php');
require_once('classes/Appointment.php');

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

$patient = new Patient('', '', '', '');
$appointment = new Appointment('', '', '', '', '');

$appointmentDetails = $appointment->getAppointmentDetails($appointmentId);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateAppointment'])) {
    $newPatientName = $_POST['patientName'];
    $newAddress = $_POST['address'];
    $newTelephone = $_POST['telephone'];
    $newDate = $_POST['date'];
    $newTime = $_POST['time'];

    $patient = new Patient($appointmentDetails['patient_id'], $newPatientName, $newTelephone, $newAddress);
    $appointment = new Appointment($appointmentDetails['id'], $newDate, $newTime, $appointmentDetails['status'], $appointmentDetails['patient_id']);

    $appointments = $appointment->getAppointmentsFromSession();
    
    foreach ($appointments as &$appntmnt) {
        if ($appntmnt['id'] === $appointmentDetails['id']) {
            $appntmnt['name'] = $patient->getName();
            $appntmnt['address'] = $patient->getAddress();
            $appntmnt['telephone'] = $patient->getTelephone();
            $appntmnt['date'] = $appointment->getDate();
            $appntmnt['time'] = $appointment->getTime();
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
    <title>Toothcare Hospital - Edit Appointment</title>
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
        <h2 class="text-center">Update Appointment | # <?=$appointmentDetails['id']?></h2>
        <hr/>
        <form method="post" id="appointmentForm">
            <label for="patientName">Patient Name:</label>
            <input type="text" id="patientName" name="patientName" class="form-control" value="<?=$appointmentDetails['name']?>" required>
            <br>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" class="form-control" value="<?=$appointmentDetails['address']?>" required>
            <br>

            <label for="telephone">Telephone:</label>
            <input type="text" id="telephone" name="telephone" class="form-control" value="<?=$appointmentDetails['telephone']?>" required>
            <br>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" class="form-control" value="<?=$appointmentDetails['date']?>" required>
            <br>

            <label for="time">Preferred Time Slot:</label>
            <select id="time" name="time" class="form-control" required>
            </select>
            <br>

            <button type="submit" name="updateAppointment" class="btn btn-primary btn-block">Update Appointment</button>
        </form>

    <script>
        // Function to initialize and populate time slots
        function initializeTimeSlots() {
        var datepicker = document.getElementById('date');
        var timeSelect = document.getElementById('time');
        var selectedDate = new Date(datepicker.value);
        var day = selectedDate.getDay(); // 0 is Sunday, 1 is Monday, etc.

        // Retrieve existing appointments for the selected date
        var existingAppointments = <?= json_encode($_SESSION['appointments'] ?? []) ?>;

        // Extract booked time slots for the selected date
        var bookedTimeSlots = existingAppointments
            .filter(function (appointment) {
                return new Date(appointment.date).toDateString() === selectedDate.toDateString();
            })
            .map(function (appointment) {
                return appointment.time;
            });

        // Define time slots based on the selected day
        var timeSlots = [];
        if (day === 1 || day === 3) {
            // Monday or Wednesday
            timeSlots = ['06:00 pm - 07:00 pm', '07:00 pm - 08:00 pm', '08:00 pm - 09:00 pm'];
        } else if (day === 6 || day === 0) {
            // Saturday or Sunday
            timeSlots = ['03:00 pm - 04:00 pm', '04:00 pm - 05:00 pm', '05:00 pm - 06:00 pm', '06:00 pm - 07:00 pm', '07:00 pm - 08:00 pm', '08:00 pm - 09:00 pm', '09:00 pm - 10:00 pm'];
        }

        // Exclude booked time slots from the available options
        var availableTimeSlots = timeSlots.filter(function (slot) {
            return !bookedTimeSlots.includes(slot);
        });

        // Clear existing options
        timeSelect.innerHTML = '';

        // Add a placeholder option
        var placeholderOption = document.createElement('option');
        placeholderOption.value = '';
        placeholderOption.text = 'Select a time slot';
        timeSelect.add(placeholderOption);

        // Include the preselected time slot even if it's booked
        var preselectedOption = document.createElement('option');
        preselectedOption.value = '<?= $appointmentDetails['time'] ?>';
        preselectedOption.text = '<?= $appointmentDetails['time'] ?>';
        preselectedOption.selected = true;
        timeSelect.add(preselectedOption);

        // Populate other available time slots in the dropdown
        availableTimeSlots.forEach(function (slot) {
            var option = document.createElement('option');
            option.value = slot;
            option.text = slot;

            // Exclude the preselected time slot from the options
            if (slot !== '<?= $appointmentDetails['time'] ?>') {
                timeSelect.add(option);
            }
        });
    }

    // Function to validate the selected date
    function validateDate() {
        var selectedDate = document.getElementById('date').value;
        var selectedDay = new Date(selectedDate).getDay();

        // Array of allowed days (Monday is 1, Sunday is 0)
        var allowedDays = [1, 3, 6, 0];

        if (!allowedDays.includes(selectedDay)) {
            alert('Please select a valid date (Monday, Wednesday, Saturday, or Sunday).');
            return false;
        }

        return true;
    }

    // Attach the validation function to the form submission
    document.getElementById('appointmentForm').addEventListener('submit', function (event) {
        if (!validateDate()) {
            event.preventDefault();
        }
    });

        // Attach the initialize function to the page load event
        window.addEventListener('load', function () {
            initializeTimeSlots();
        });

        document.getElementById('date').addEventListener('input', function () {
            initializeTimeSlots(); // Re-initialize time slots on date change
        });
    </script>

</body>
</html>