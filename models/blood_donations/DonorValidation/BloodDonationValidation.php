<?php

class BloodDonationValidation extends DonorValidationTemplate {
    protected function validateDonationSpecificCriteria(Donor $donor): bool {
        return isset($donor->blood_type) && !empty($donor->blood_type);
    }
}
 ?>