package com.blooddonation.system.models.skills;

import com.blooddonation.system.models.people.IVolunteer;

import java.util.List;

public class Driving extends SkillsDecorator {
    public Driving(IVolunteer volunteer) {
        super(volunteer);
    }

    @Override
    public List<String> skill() {
        List<String> skills = volunteer.skill();
        if (skills.contains("Driving")) {
            System.out.println("Skill already exists");
            return skills;
        }
        skills.add("Driving");
        return skills;
    }
}
