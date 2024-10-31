package com.blooddonation.system.models.events;
import com.blooddonation.system.models.Address;

import lombok.Getter;

import java.util.Date;

@Getter
@SuppressWarnings("unused")
public class OutreachEvent extends Event {
    private String targetAudience;

    public OutreachEvent(String name, String description, Date date, Address address, String targetAudience) {
        super(name, description, date, address);
        this.targetAudience = targetAudience;
    }

    public OutreachEvent() {
    }
}
