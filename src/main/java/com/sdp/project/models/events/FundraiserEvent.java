package com.sdp.project.models.events;

public class FundraiserEvent extends Event {
    private float goalAmount;
    private float raisedAmount;

    // Getters and Setters
    public float getGoalAmount() {
        return goalAmount;
    }

    public void setGoalAmount(float goalAmount) {
        this.goalAmount = goalAmount;
    }

    public float getRaisedAmount() {
        return raisedAmount;
    }

    public void setRaisedAmount(float raisedAmount) {
        this.raisedAmount = raisedAmount;
    }

    public boolean updateRaisedAmount(float amount) {
        if (amount > 0) {
            this.raisedAmount += amount;
            return true;
        }
        return false;
    }

    @Override
    public String getDetails() {
        return "Fundraiser Event: " + getTitle() + ", Goal: " + goalAmount + ", Raised: " + raisedAmount;
    }
}
