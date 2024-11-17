package com.sdp.project.models.volunteers;

import com.sdp.project.models.Person;
import org.springframework.data.annotation.Id;
import org.springframework.data.relational.core.mapping.Table;

import java.util.List;

@Table("VOLUNTEERS")
public class Volunteer extends Person {
    @Id
    private Integer id;
    private List<Skill> skills; // This will be handled manually in the service layer

    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public List<Skill> getSkills() {
        return skills;
    }

    public void setSkills(List<Skill> skills) {
        this.skills = skills;
    }
}
