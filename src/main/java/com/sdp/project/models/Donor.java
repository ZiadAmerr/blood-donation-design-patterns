package com.sdp.project.models;

import com.sdp.project.models.money.MoneyDonation;
import org.springframework.data.annotation.Id;
import org.springframework.data.relational.core.mapping.Table;
import org.springframework.data.relational.core.mapping.Column;

import java.util.List;

@Table("DONORS")  // Specifies the table in the database
public class Donor extends Person {

    @Id
    private int id;

    // Methods for donation
    public void donateBlood() {
        // Donation logic
    }

    public void donateMoney() {
        // Donation logic
    }

    // Getters and Setters

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

}
