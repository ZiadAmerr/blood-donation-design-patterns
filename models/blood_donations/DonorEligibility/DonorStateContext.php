<?php

require_once 'DonorState.php';

class DonorStateContext {
    private $state;

    public function __construct(DonorState $state) {
        $this->state = $state;
    }

    public function setState(DonorState $state) {
        $this->state = $state;
    }

    public function donate() {
        $this->state->donate();
    }
}
?>