package com.blooddonation.system.models.events;
import com.blooddonation.system.models.Address;

import lombok.Getter;

import java.util.Date;

@Getter
@SuppressWarnings("unused")
public class FundraiserEvent extends Event {
    private String cause;

    public FundraiserEvent(String name, String description, Date date, Address address, String cause) {
        super(name, description, date, address);
        this.cause = cause;
    }

    public FundraiserEvent() {
    }

    public String getType() {
        return "Fundraiser";
    }
}
