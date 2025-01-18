<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/blood_donations/DonorEligibility/DonorStateContext.php";

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

    protected function validateAge(Donor $donor): bool {
        return $donor->age >= 18 && $donor->age <= 65;
    }
    

    protected function validateWeight(Donor $donor): bool {
        return $donor->weight >= 50;
    }

    protected function getEligibility(Donor $donor): string {
        $donorStateContext = new DonorContext($donor);
        $state = $donorStateContext->getState();
        return $state->getAsString();
    }

    protected function validateLastDonationDate(Donor $donor): bool {
        return $donor->getDonorLastDonationInterval() >= 60; // Donor can donate once every 2 months.
    }
    protected function calculateRemainingTime(Donor $donor): ?int {
        
        if ($donor->age < 18) {
            $remainingYears = 18 - $donor->age;
            return $remainingYears*365;
        }

        $interval = $donor->getDonorLastDonationInterval();

        if ($interval < 60) {
            $remainingDays = 60 - $interval;
            return $remainingDays;
        }

        return null;
    }

    // Abstract method to be implemented by subclasses.
    abstract protected function validateDonationSpecificCriteria(Donor $donor): bool;
}
?>