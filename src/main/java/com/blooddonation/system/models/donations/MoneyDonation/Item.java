package com.blooddonation.system.models.donations.MoneyDonation;

import lombok.Getter;
import lombok.Setter;

@Setter
@Getter
public class Item {

    private String name;
    private float price;

    public Item(String name, float price) {
        this.name = name;
        this.price = price;
    }

    public float getPrice() {
        return price;
    }
}
