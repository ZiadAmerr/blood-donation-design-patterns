package com.sdp.project.models.events;

public class WorkshopEvent extends Event {
    private String instructor;
    private int maxAttendees;

    // Getters and Setters
    public String getInstructor() {
        return instructor;
    }

    public void setInstructor(String instructor) {
        this.instructor = instructor;
    }

    public int getMaxAttendees() {
        return maxAttendees;
    }

    public void setMaxAttendees(int maxAttendees) {
        this.maxAttendees = maxAttendees;
    }

    @Override
    public String getDetails() {
        return "Workshop Event: " + getTitle() + ", Instructor: " + instructor + ", Max Attendees: " + maxAttendees;
    }
}
