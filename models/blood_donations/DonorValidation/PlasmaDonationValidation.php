<?php

class PlasmaDonationValidation extends DonorValidationTemplate {
    protected function validateDonationSpecificCriteria(Donor $donor): bool {
        return $donor->weight >= 50;
    }
}

?>