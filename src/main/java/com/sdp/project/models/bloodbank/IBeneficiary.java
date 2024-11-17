package com.sdp.project.models.bloodbank;

public interface IBeneficiary {
    void update(BloodStock bloodStock); // React to blood stock updates
    String getName(); // Get the name of the beneficiary
}