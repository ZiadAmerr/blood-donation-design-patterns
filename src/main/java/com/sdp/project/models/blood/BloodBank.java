package com.sdp.project.models.blood;

import java.util.Map;
import java.util.HashMap;

public class BloodBank implements IBeneficiary {

    private String name;
    private Map<BloodType, Integer> bloodInventory = new HashMap<>();

    {
        for (BloodType type : BloodType.values()) {
            bloodInventory.put(type, 0);
        }
    }

    public BloodBank(String name) {
        this.name = name;
    }

    @Override
    public void update(BloodStock bloodStock) {
        System.out.println(name + " received blood stock update: " + bloodStock.getBloodAmount());
        // Logic to check stock and replenish if needed
    }

    public String getName() {
        return name;
    }

    // Additional methods for managing inventory
    public void replenishStock(BloodType type, int amount) {
        bloodInventory.put(type, bloodInventory.getOrDefault(type, 0) + amount);
    }

    public boolean withdrawBlood(BloodType type, int amount) {
        if (bloodInventory.getOrDefault(type, 0) >= amount) {
            bloodInventory.put(type, bloodInventory.get(type) - amount);
            return true;
        }
        return false;
    }
}
