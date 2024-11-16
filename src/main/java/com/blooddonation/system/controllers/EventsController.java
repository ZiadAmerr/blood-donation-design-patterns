package com.blooddonation.system.controllers;

import com.blooddonation.system.models.events.Event;
import com.blooddonation.system.models.events.EventFactory;
import com.blooddonation.system.models.events.EventAttendanceManager;
import com.blooddonation.system.models.people.Person;
import com.blooddonation.system.models.Address;
import org.springframework.web.bind.annotation.*;

import java.util.*;

@RestController
@RequestMapping("/events")
public class EventController {

    private Map<Integer, Event> events = new HashMap<>();

    @GetMapping
    public Collection<Event> getAllEvents() {
        return events.values();
    }

    @GetMapping("/{id}")
    public Event getEventById(@PathVariable int id) {
        return events.get(id);
    }

    @PostMapping
    public Event createEvent(@RequestParam String type, @RequestBody Map<String, Object> eventData) {
        Event event = EventFactory.createEvent(type);
        event.setName((String) eventData.get("name"));
        event.setDescription((String) eventData.get("description"));
        event.setDate(new Date((Long) eventData.get("date")));
        Map<String, Object> addressData = (Map<String, Object>) eventData.get("address");
        Address address = new Address();
        // Set address fields here
        event.setAddress(address);
        events.put(event.getId(), event);
        return event;
    }

    @PutMapping("/{id}")
    public Event updateEvent(@PathVariable int id, @RequestBody Map<String, Object> eventData) {
        Event event = events.get(id);
        if (event != null) {
            event.setName((String) eventData.get("name"));
            event.setDescription((String) eventData.get("description"));
            event.setDate(new Date((Long) eventData.get("date")));
            Map<String, Object> addressData = (Map<String, Object>) eventData.get("address");
            Address address = new Address();
            // Set address fields here
            event.setAddress(address);
        }
        return event;
    }

    @DeleteMapping("/{id}")
    public void deleteEvent(@PathVariable int id) {
        events.remove(id);
    }

    @PostMapping("/{id}/attend")
    public void attendEvent(@PathVariable int id, @RequestBody Person person) {
        Event event = events.get(id);
        if (event != null) {
            EventAttendanceManager.setCurrentEvent(event);
            EventAttendanceManager.attendEvent(person);
        }
    }

    @PostMapping("/{id}/cancel")
    public void cancelAttendance(@PathVariable int id, @RequestBody Person person) {
        Event event = events.get(id);
        if (event != null) {
            EventAttendanceManager.setCurrentEvent(event);
            EventAttendanceManager.cancelAttendance(person);
        }
    }

    @GetMapping("/{id}/attendees")
    public Set<Person> getAttendees(@PathVariable int id) {
        Event event = events.get(id);
        if (event != null) {
            EventAttendanceManager.setCurrentEvent(event);
            return EventAttendanceManager.getAttendees();
        }
        return Collections.emptySet();
    }
}