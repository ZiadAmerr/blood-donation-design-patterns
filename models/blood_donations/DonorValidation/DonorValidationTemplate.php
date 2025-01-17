<?php

abstract class DonorValidationTemplate {
    // Template method defines the steps for validation.
    public function validateDonor(Donor $donor, string $donationType): string {
        $isAgeValid = $this->validateAge($donor);
        $isWeightValid = $this->validateWeight($donor);
        $isLastDonationDateValid = $this->validateLastDonationDate($donor);
        $isSpecificCriteriaValid = $this->validateDonationSpecificCriteria($donor);
        
        $eligibility = $this->getEligibility($donor);

        $report = new PlainReport(
            $isAgeValid,
            $isWeightValid,
            $isLastDonationDateValid,
            $isSpecificCriteriaValid,
            $eligibility,
            $donationType,
            $this->calculateRemainingTime($donor)
        );

        return $report->generateXML();
    }

    protected function validateAge(array $donor): bool {
        return $donor['age'] >= 18 && $donor['age'] <= 65;
    }

    protected function validateWeight(array $donor): bool {
        return $donor['weight'] >= 50;
    }

    protected function getEligibility(Donor $donor): string {
        $donorStateContext = new DonorStateContext($this->$donor);
        $state = $donorStateContext->getState();
        return $state->getAsStr();
    }

    protected function validateLastDonationDate(array $donor): bool {
        $lastDonation = new DateTime($donor['last_donation_date']);
        $today = new DateTime();
        $interval = $lastDonation->diff($today);

        return $interval->days >= 60; // Donor can donate once every 2 months.
    }
    protected function calculateRemainingTime(array $donor): ?string {
        if ($donor['age'] < 18) {
            $remainingYears = 18 - $donor['age'];
            return "$remainingYears years until eligible";
        }

        $lastDonation = new DateTime($donor['last_donation_date']);
        $today = new DateTime();
        $interval = $lastDonation->diff($today);

        if ($interval->days < 60) {
            $remainingDays = 60 - $interval->days;
            return "$remainingDays days until eligible";
        }

        return null;
    }

    // Abstract method to be implemented by subclasses.
    abstract protected function validateDonationSpecificCriteria(Donor $donor): bool;
}
?>