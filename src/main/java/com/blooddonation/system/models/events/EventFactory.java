package com.blooddonation.system.models.events;

import java.util.ArrayList;
import java.util.List;
import java.util.Set;
import java.util.HashMap;
import java.util.Map;
import org.reflections.Reflections;

@SuppressWarnings("unused")
public class EventFactory {
    private static final Map<String, Class<? extends Event>> eventLookup = initializeEventLookup();

    private static String cleanupName(String name) {
        return name.replace("Event", "").toLowerCase();
    }

    private static String getEventType(Class<? extends Event> eventClass) {
        return cleanupName(eventClass.getSimpleName());
    }

    // Helper method to retrieve subclasses and initialize event lookup
    private static Map<String, Class<? extends Event>> initializeEventLookup() {
        Map<String, Class<? extends Event>> lookupMap = new HashMap<>();
        for (Class<? extends Event> subclass : getEventSubclasses()) {
            String eventType = getEventType(subclass);
            lookupMap.put(eventType, subclass);
        }
        return lookupMap;
    }

    // Shared method to retrieve all subclasses of Event
    private static Set<Class<? extends Event>> getEventSubclasses() {
        Reflections reflections = new Reflections(EventFactory.class.getPackageName());
        return reflections.getSubTypesOf(Event.class);
    }

    public static Event createEvent(String type) {
        Class<? extends Event> eventClass = eventLookup.get(type.toLowerCase());

        if (eventClass != null) {
            try {
                return eventClass.getDeclaredConstructor().newInstance();
            } catch (ReflectiveOperationException e) {
                throw new RuntimeException("Failed to create event of type: " + type, e);
            }
        }

        throw new IllegalArgumentException("No event found for type: " + type);
    }

    public static List<String> getEventTypes() {
        List<String> eventTypes = new ArrayList<>();
        for (Class<? extends Event> subclass : getEventSubclasses()) {
            eventTypes.add(getEventType(subclass));
        }
        return eventTypes;
    }
}