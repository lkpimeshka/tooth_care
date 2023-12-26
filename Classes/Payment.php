<?php 

class Payment
{
    protected $id;
    protected $paymentRefNo;
    protected $appointmentId;

    public function __construct($id, $paymentRefNo, $appointmentId)
    {
        $this->id = $id;
        $this->paymentRefNo = $paymentRefNo;
        $this->appointmentId = $appointmentId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPaymentRefNo()
    {
        return $this->paymentRefNo;
    }

    public function getAppointmentId()
    {
        return $this->appointmentId;
    }
}

class RegistrationFee extends Payment
{
    private $registrationFee;

    public function __construct($id, $paymentRefNo, $appointmentId, $registrationFee)
    {
        parent::__construct($id, $paymentRefNo, $appointmentId);
        $this->registrationFee = 1000;
    }

    public function getRegistrationFee()
    {
        return $this->registrationFee;
    }

}

class TreatmentFee extends Payment
{
    private $treatmentFee;

    public function __construct($id, $paymentRefNo, $appointmentId, $treatmentFee)
    {
        parent::__construct($id, $paymentRefNo, $appointmentId);
        $this->treatmentFee = $treatmentFee;
    }

    public function getTreatmentFee()
    {
        return $this->treatmentFee;
    }
}

?>