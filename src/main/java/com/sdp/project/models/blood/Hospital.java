package com.sdp.project.models.blood;

import java.util.Map;

public class Hospital implements IBeneficiary {

    private String name;
    private Map<BloodType, Integer> bloodInventory;

    public Hospital(String name) {
        this.name = name;
    }

    @Override
    public void update(BloodStock bloodStock) {
        System.out.println(name + " received blood stock update: " + bloodStock.getBloodAmount());
        // Logic to synchronize blood inventory with central stock
    }

    public String getName() {
        return name;
    }

    // Additional methods for managing inventory
    public void requestBlood(BloodType type, int amount) {
        System.out.println(name + " requesting " + amount + " units of " + type);
    }
}
