<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Toothcare Hospital - Create Appointment</title>
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
        <h2 class="text-center">Create Appointment</h2>
        <hr/>
        <form method="post" id="appointmentForm" action="create_appointment_submit.php">
            <label for="patientName">Patient Name:</label>
            <input type="text" id="patientName" name="patientName" class="form-control" required>
            <br>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" class="form-control" required>
            <br>

            <label for="telephone">Telephone:</label>
            <input type="text" id="telephone" name="telephone" class="form-control" required>
            <br>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" class="form-control" required>
            <br>

            <label for="time">Preferred Time Slot:</label>
            <select id="time" name="time" class="form-control" placeholder="Select a time slot" required>
                <!-- Options will be dynamically populated based on the selected day -->
            </select>
            <br>

            <button type="submit" name="bookAppointment" class="btn btn-primary btn-block">Book Appointment</button>
        </form>
    </div>

    <script>
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
        document.getElementById('appointmentForm').addEventListener('submit', function(event) {
            if (!validateDate()) {
                event.preventDefault();
            }
        });

        document.getElementById('date').addEventListener('input', function() {
            var datepicker = document.getElementById('date');
            var timeSelect = document.getElementById('time');
            var selectedDate = new Date(datepicker.value);
            var day = selectedDate.getDay(); // 0 is Sunday, 1 is Monday, etc.

            // Retrieve existing appointments for the selected date
            var existingAppointments = <?php echo json_encode($_SESSION['appointments'] ?? []); ?>;

            // Extract booked time slots for the selected date
            var bookedTimeSlots = existingAppointments
                .filter(function(appointment) {
                    return new Date(appointment.date).toDateString() === selectedDate.toDateString();
                })
                .map(function(appointment) {
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
            var availableTimeSlots = timeSlots.filter(function(slot) {
                return !bookedTimeSlots.includes(slot);
            });

            // Clear existing options
            timeSelect.innerHTML = '';

            // Add a placeholder option
            var placeholderOption = document.createElement('option');
            placeholderOption.value = '';
            placeholderOption.text = 'Select a time slot';
            timeSelect.add(placeholderOption);

            // Populate time slots in the dropdown
            availableTimeSlots.forEach(function(slot) {
                var option = document.createElement('option');
                option.value = slot;
                option.text = slot;
                timeSelect.add(option);
            });
        });
    </script>
</body>

</html>