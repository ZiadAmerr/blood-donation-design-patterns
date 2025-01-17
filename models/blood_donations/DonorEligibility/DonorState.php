<?php

interface DonorState {
    public function donate();
    public function isValid(Donor $donor): bool;
}
