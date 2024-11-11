package com.blooddonation.system.models.skills;

import com.blooddonation.system.models.people.IVolunteer;

import java.util.List;

public class Nursing extends SkillsDecorator {
    public Nursing(IVolunteer volunteer) {
        super(volunteer);
    }

    @Override
    public List<String> skill() {
        List<String> skills = volunteer.skill();
        if (skills.contains("Nursing")) {
            System.out.println("Skill already exists");
            return skills;
        }
        skills.add("Nursing");
        return skills;
    }
}
