package com.sdp.project.models.events;

public class OutReachEvent extends Event {
    private String audience;

    // Getters and Setters
    public String getAudience() {
        return audience;
    }

    public void setAudience(String audience) {
        this.audience = audience;
    }

    @Override
    public String getDetails() {
        return "Outreach Event: " + getTitle() + ", Audience: " + audience;
    }
}
