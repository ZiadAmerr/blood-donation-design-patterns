<?php

class BloodDonationValidation extends DonorValidationTemplate {
    protected function validateDonationSpecificCriteria(array $donor): bool {
        return isset($donor['blood_type']) && !empty($donor['blood_type']);
    }
}
 ?>