package com.sdp.project.models.money;

import org.springframework.stereotype.Component;

@Component
public class Cash implements MoneyDonationStrategy {

    @Override
    public boolean donate(float amount) {
        // Logic for cash donation (simplified)
        System.out.println("Donating " + amount + " through Cash.");
        return true;  // assuming donation is successful
    }
}
