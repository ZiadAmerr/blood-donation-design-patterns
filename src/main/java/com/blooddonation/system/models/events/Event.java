package com.blooddonation.system.models.events;

import com.blooddonation.system.models.Address;
import com.blooddonation.system.models.people.Person;

import lombok.Getter;
import lombok.Setter;

import java.util.Date;
import java.util.HashSet;
import java.util.Set;

@Getter
@SuppressWarnings("unused")
public abstract class Event {
    private @Setter String name;
    private @Setter String description;
    private @Setter Date date;
    private @Setter Address address;

    private final int id;

    private static int eventCount = 0;

    protected final Set<Person> attendees = new HashSet<>();

    private final Set<Event> events = new HashSet<>();

    public Event(String name, String description, Date date, Address address) {
        this.id = eventCount++;
        this.name = name;
        this.description = description;
        this.date = date;
        this.address = address;
        events.add(this);
    }

    public Event() {
        this("Default Name", "Default Description", new Date(), new Address());
    }


}
