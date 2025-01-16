<?php

class PlasmaDonationValidation extends DonorValidationTemplate {
    protected function validateDonationSpecificCriteria($donor): void {
        // For plasma donation, a common criterion could be that the donor's weight should be above a certain threshold.
        if ($donor['weight'] >= 55) {
            echo "Weight is sufficient for plasma donation.\n";
        } else {
            echo "Weight is insufficient for plasma donation.\n";
        }
    }
}

?>