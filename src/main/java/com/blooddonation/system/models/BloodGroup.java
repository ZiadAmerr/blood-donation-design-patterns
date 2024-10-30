package com.blooddonation.system.models;

import lombok.Getter;

@Getter
public class BloodGroup {
    private final String group;
    private final String rh;

    public BloodGroup(String group, String rh) {
        this.group = group;
        this.rh = rh;
    }

    public boolean canGiveBloodTo(BloodGroup other) {
        return this.group.equals("O") ||
                (this.group.equals("A") && other.group.equals("A")) ||
                (this.group.equals("B") && other.group.equals("B")) ||
                this.group.equals("AB");
    }

    public boolean canReceiveBloodFrom(BloodGroup other) {
        return other.canGiveBloodTo(this);
    }
}
