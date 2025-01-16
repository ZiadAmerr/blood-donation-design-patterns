<?php

require_once 'DonorState.php';

class Eligible implements DonorState {
    public function can_donate(): bool {
        echo "You are eligible to donate blood. Proceeding with the donation.\n";
    }
}

?>