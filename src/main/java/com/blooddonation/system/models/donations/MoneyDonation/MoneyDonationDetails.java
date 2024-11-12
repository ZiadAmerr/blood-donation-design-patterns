package com.blooddonation.system.models.donations.MoneyDonation;
import lombok.Getter;
import lombok.Setter;

import java.time.LocalDateTime;
import java.util.HashMap;
import java.util.Map;

@Setter
@Getter
public class MoneyDonationDetails {
    // Getters and setters for the fields can be added here
    private Map<Item, Integer> items = new HashMap<>();
    private LocalDateTime datetime = LocalDateTime.now();
    private float totalAmount = 0;

    public void setTotalAmount(float amount)
    {
        this.totalAmount = calc_amount();
    }

    public float calc_amount() {
        float total = 0;
        for (Map.Entry<Item, Integer> entry : items.entrySet()) {
            total += entry.getKey().getPrice() * entry.getValue();
        }
        return total;
    }
}
