<?php

class PlainReport implements IReport {
    private bool $isAgeValid;
    private bool $isWeightValid;
    private bool $isLastDonationDateValid;
    private bool $isSpecificCriteriaValid;
    private string $eligibility;
    private string $donationType;
    private ?string $remainingTime;

    public function __construct(
        bool $isAgeValid,
        bool $isWeightValid,
        bool $isLastDonationDateValid,
        bool $isSpecificCriteriaValid,
        string $eligibility,
        string $donationType,
        ?string $remainingTime
    ) {
        $this->isAgeValid = $isAgeValid;
        $this->isWeightValid = $isWeightValid;
        $this->isLastDonationDateValid = $isLastDonationDateValid;
        $this->isSpecificCriteriaValid = $isSpecificCriteriaValid;
        $this->eligibility = $eligibility;
        $this->donationType = $donationType;
        $this->remainingTime = $remainingTime;
    }

    public function generateXML(): string {
        return "<report>"
            . "<ageValid>" . ($this->isAgeValid ? "true" : "false") . "</ageValid>"
            . "<weightValid>" . ($this->isWeightValid ? "true" : "false") . "</weightValid>"
            . "<lastDonationDateValid>" . ($this->isLastDonationDateValid ? "true" : "false") . "</lastDonationDateValid>"
            . "<specificCriteriaValid>" . ($this->isSpecificCriteriaValid ? "true" : "false") . "</specificCriteriaValid>"
            . "<eligibility>" . $this->eligibility . "</eligibility>"
            . "<donationType>" . $this->donationType . "</donationType>"
            . ($this->remainingTime ? "<remainingTime>" . $this->remainingTime . "</remainingTime>" : "")
            . "</report>";
    }
}

?>