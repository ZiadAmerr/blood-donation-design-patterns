<?php
// DonorValidationTemplate as a base class.
abstract class DonorValidationTemplate {
    // Template method defines the steps for validation.
    public function templateMethod($donor): void {
        $this->validateAge($donor);
        $this->validateWeight($donor);
        $this->validateHealth($donor);
        $this->validateLastDonationDate($donor);
        $this->validateDonationSpecificCriteria($donor);
    }

    protected function validateAge($donor): void {
        if ($donor['age'] < 18) {
            echo "Age is less than 18, donor is not eligible.\n";
        } else {
            echo "Validating age.\n";
        }
    }

    protected function validateWeight($donor): void {
        if ($donor['weight'] < 50) {
            echo "Weight is less than 50kg, donor is not eligible.\n";
        } else {
            echo "Validating weight.\n";
        }
    }

    protected function validateHealth($donor): void {
        if ($donor['health_status'] != 'healthy') {
            echo "Donor health is not optimal, cannot proceed with donation.\n";
        } else {
            echo "Validating health.\n";
        }
    }

    protected function validateLastDonationDate($donor): void {
        $lastDonation = new DateTime($donor['last_donation_date']);
        $today = new DateTime();
        $interval = $lastDonation->diff($today);

        if ($interval->days < 60) { // Example: Donor can donate once every 2 months.
            echo "Donor has donated too recently. Please wait for at least 2 months.\n";
        } else {
            echo "Validating last donation date.\n";
        }
    }

    // Abstract method to be implemented by subclasses.
    abstract protected function validateDonationSpecificCriteria($donor): void;
}
?>