package com.sdp.project.models.money;

import org.springframework.data.annotation.Id;
import org.springframework.data.relational.core.mapping.Table;

import java.util.Date;

@Table("MONEY_DONATIONS")
public class MoneyDonation {

    @Id
    private int id;
    private double amount;
    private String donationDate;
    private MoneyDonationStrategy moneyDonationStrategy;

    // Foreign key to Donor table
    private int donorId;

    // Getters and Setters

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public double getAmount() {
        return amount;
    }

    public void setAmount(double amount) {
        this.amount = amount;
    }

    public String getDonationDate() {
        return donationDate;
    }

    public void setDonationDate(String donationDate) {
        this.donationDate = donationDate;
    }

    public int getDonorId() {
        return donorId;
    }

    public void setDonorId(int donorId) {
        this.donorId = donorId;
    }

    public MoneyDonationStrategy getDonationMethod() {
        return moneyDonationStrategy;
    }

    public void setDonationMethod(MoneyDonationStrategy moneyDonationStrategy) {
        this.moneyDonationStrategy = moneyDonationStrategy;
    }

    public void confirmDonation(float amount){
        moneyDonationStrategy.donate(amount);
    }
}
