<?php

require_once 'DonorState.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Donor.php";
require_once 'DonorStates.php';

class DonorContext {
    private Donor $donor;
    private DonorState $state;
    private static $previous_operations = [];
    private bool $has_changed_since_last_check = false;

    public function __construct(Donor $donor) {
        $this->donor = $donor;
        $this->determineState();
    }

    public function getState() {
        return $this->state;
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
                break;
            }
        }

        // Push the state and the donor to the previous operations stack
        array_push(self::$previous_operations, [
            'state' => $this->state,
            'donor' => $this->donor
        ]);

        // If the stack is too large, remove the oldest operation
        if (count(self::$previous_operations) > 30) {
            array_shift(self::$previous_operations);
        }

        // Loop through the contents of the stack in reverse order
        // if the donor is the same as the current donor but the state is different
        // do something
        for ($i = count(self::$previous_operations) - 1; $i >= 0; $i--) {
            if (self::$previous_operations[$i]['donor'] === $this->donor && self::$previous_operations[$i]['state'] !== $this->state) {
                $this->has_changed_since_last_check = true;
                break;
            }
        }
    }

    public function hasChangedSinceLastCheck() {
        return $this->has_changed_since_last_check;
    }

    public function donate() {
        $this->state->donate();
    }
}
