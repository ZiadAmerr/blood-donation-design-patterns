package com.blooddonation.system.models.people;

import com.blooddonation.system.models.Address;
import com.blooddonation.system.models.events.*;

import lombok.Getter;
import lombok.Setter;

@Getter
@Setter
@SuppressWarnings("unused")
public class Employee extends Person {
    private int privilege;

    public Employee(String name, String email, String phone, Address address) {
        super(name, email, phone, address);
        this.privilege = 0;
    }

    public Event createEvent(String eventType) {
        return EventFactory.createEvent(eventType);
    }
}
