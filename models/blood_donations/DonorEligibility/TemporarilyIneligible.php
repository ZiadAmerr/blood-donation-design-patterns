<?php

require_once 'DonorState.php';

class TemporarilyIneligible implements DonorState {
    public function can_donate(): bool {
        echo "You are temporarily ineligible to donate blood. Please wait until you become eligible.\n";
    }
}

?>