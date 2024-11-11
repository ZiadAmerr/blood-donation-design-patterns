package com.blooddonation.system.models.people;

import com.blooddonation.system.models.Address;
import lombok.Getter;
import lombok.Setter;

import java.util.ArrayList;
import java.util.List;

public class Volunteer extends Person implements IVolunteer {
    private boolean isAvailable;
    private List<String> skills;

    public Volunteer(boolean isAvailable) {
        this.isAvailable = isAvailable;
        this.skills = new ArrayList<>();
    }

    @Override
    public List<String> skill() {
        return skills;
    }
}
