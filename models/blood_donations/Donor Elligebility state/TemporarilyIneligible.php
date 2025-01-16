<?php

require_once 'DonorState.php';

class TemporarilyIneligible implements DonorState {
    public function donate() {
        echo "You are temporarily ineligible to donate blood. Please wait until you become eligible.\n";
    }
}

?>