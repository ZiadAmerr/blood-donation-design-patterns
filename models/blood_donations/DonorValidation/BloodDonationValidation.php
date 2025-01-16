<?php

class BloodDonationValidation extends DonorValidationTemplate {
    protected function validateDonationSpecificCriteria($donor): void {
        if ($donor['blood_type'] == 'O+' || $donor['blood_type'] == 'O-') {
            echo "Blood type is compatible for blood donation.\n";
        } else {
            echo "Blood type is not compatible for blood donation.\n";
        }
    }
}
 ?>