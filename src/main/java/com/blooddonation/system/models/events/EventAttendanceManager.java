package com.blooddonation.system.models.events;
import com.blooddonation.system.models.people.Person;
import lombok.Setter;

//Import arraylist and list
import java.util.Set;

@SuppressWarnings("unused")
public class EventAttendanceManager {
    private static @Setter Event currentEvent;

    public static void attendEvent(Person person) {
        currentEvent.attendees.add(person);
    }

    public static void cancelAttendance(Person person) {
        currentEvent.attendees.remove(person);
    }

    public static Set<Person> getAttendees() {
        return currentEvent.attendees;
    }

    public boolean isPersonAttending(Person person) {
        return currentEvent.attendees.contains(person);
    }
}
