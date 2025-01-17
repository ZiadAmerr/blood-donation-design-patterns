<?php

require_once 'DonorState.php';
require_once $_DOCUMENT_ROOT . "/models/people/Donor.php";
require_once 'DonorStates.php';

class DonorContext {
    private Donor $donor;
    private DonorState $state;

    public function __construct(Donor $donor) {
        $this->donor = $donor;
        $this->determineState();
    }

    private function determineState() {
        $states = [
            new PermanentlyIneligible(), // Highest priority
            new TemporarilyIneligible(),
            new Eligible() // Lowest priority
        ];

        foreach ($states as $state) {
            if ($state->isValid($this->donor)) {
                $this->state = $state;
                return;
            }
        }
    }

    public function donate() {
        $this->state->donate();
    }
}
