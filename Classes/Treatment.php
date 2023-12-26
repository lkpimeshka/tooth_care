<?php

abstract class Treatment {
    protected $treatmentType;

    public function __construct($treatmentType) {
        $this->treatmentType = $treatmentType;
    }

    public abstract function getTreatmentFee();
}

class CleaningTreatment extends Treatment {
    public function getTreatmentFee() {
        return 5000;
    }
}

class WhiteningTreatment extends Treatment {
    public function getTreatmentFee() {
        return 5500;
    }
}

class FillingTreatment extends Treatment {
    public function getTreatmentFee() {
        return 6000;
    }
}

class NerveFillingTreatment extends Treatment {
    public function getTreatmentFee() {
        return 6500;
    }
}

class RootCanalTherapyTreatment extends Treatment {
    public function getTreatmentFee() {
        return 7000;
    }
}
?>