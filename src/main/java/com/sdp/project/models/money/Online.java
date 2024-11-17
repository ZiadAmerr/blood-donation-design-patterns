package com.sdp.project.models.money;

import org.springframework.stereotype.Component;

@Component
public class Online implements MoneyDonationStrategy {

    @Override
    public boolean donate(float amount) {
        System.out.println("Donating " + amount + " through Online.");
        return true;  // assuming donation is successful
    }
}
