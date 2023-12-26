<?php

require_once('classes/Treatment.php');

class TreatmentFactory {
    public static function createTreatment($treatmentType) {
        switch ($treatmentType) {
            case 'Cleaning':
                return new CleaningTreatment($treatmentType);
            case 'Whitening':
                return new WhiteningTreatment($treatmentType);
            case 'Filling':
                return new FillingTreatment($treatmentType);
            case 'Nerve Filling':
                return new NerveFillingTreatment($treatmentType);
            case 'Root Canal Therapy':
                return new RootCanalTherapyTreatment($treatmentType);
            default:
                throw new Exception("Unknown treatment type: $treatmentType");
        }
    }
}

?>
