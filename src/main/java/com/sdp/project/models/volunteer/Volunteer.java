
package com.sdp.project.models.volunteer;

public class Volunteer implements IVolunteer {
    private boolean isAvailable;
    private List<String> skills;

    public Volunteer() {
        skills = new ArrayList<>();
    }

    // Getters and Setters
    public boolean isAvailable() {
        return isAvailable;
    }

    public void setAvailable(boolean available) {
        isAvailable = available;
    }

    public List<String> getSkills() {
        return skills;
    }

    @Override
    public void addSkill(String skill) {
        skills.add(skill);
    }
}
