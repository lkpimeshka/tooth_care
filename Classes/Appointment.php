<?php

class Appointment {
    private $appointmentId;
    private $date;
    private $time;
    private $status;
    private $patientId;

    public function __construct($appointmentId, $date, $time, $status, $patientId) {
        $this->appointmentId = $appointmentId;
        $this->date = $date;
        $this->time = $time;
        $this->status = $status;
        $this->patientId = $patientId;
    }

    public function getAppointmentId() {
        return $this->appointmentId;
    }

    public function getDate() {
        return $this->date;
    }

    public function getTime() {
        return $this->time;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getPatientId() {
        return $this->patientId;
    }

    public function getAppointmentsFromSession() {
        return isset($_SESSION['appointments']) ? $_SESSION['appointments'] : [];
    }

    public function getAppointmentDetails($appointmentId) {
        $appointments = $this->getAppointmentsFromSession();
            foreach ($appointments as $appointment) {
                if ($appointment['id'] === $appointmentId) {
                    return $appointment;
                }
            }

        return null;
    }
    
}

?>
