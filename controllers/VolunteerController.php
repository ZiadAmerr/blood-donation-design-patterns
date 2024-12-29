<?php
require_once __DIR__ . '/../models/people/Volunteer.php';
require_once __DIR__ . '/../models/skills/Driving.php';
require_once __DIR__ . '/../models/skills/Nursing.php';

class VolunteerController
{
    public function handleRequest(): void
    {
        $action = $_GET['action'] ?? 'list';

        switch ($action) {
            case 'add_skill':
                $this->addSkill();
                break;
            case 'remove_skill':
                $this->removeSkill();
                break;
            default:
                $this->listVolunteerSkills();
                break;
        }
    }

    private function listVolunteerSkills(): void
    {
        $volunteerId = $_GET['volunteer_id'] ?? 1;
        $volunteer = new Volunteer((int)$volunteerId);

        echo "<h2>Skills for Volunteer #{$volunteerId}</h2>";
        foreach ($volunteer->skills as $skill) {
            echo "<p>$skill</p>";
        }

        // Provide a quick form to add new skills
?>
        <form method="POST" action="?action=add_skill&volunteer_id=<?php echo $volunteerId; ?>">
            <input type="text" name="skill_name" placeholder="Skill name">
            <select name="skill_type">
                <option value="driving">Driving</option>
                <option value="nursing">Nursing</option>
                <!-- Add more skill types if you have more decorators -->
            </select>
            <button type="submit">Add Skill</button>
        </form>
<?php
    }

    private function addSkill(): void
    {
        $volunteerId = $_GET['volunteer_id'] ?? 1;
        $skillName = $_POST['skill_name'] ?? '';
        $skillType = $_POST['skill_type'] ?? 'driving';

        $volunteer = new Volunteer((int)$volunteerId);

        // Wrap volunteer with decorator
        switch ($skillType) {
            case 'driving':
                $decoratedVolunteer = new Driving($volunteer);
                break;
            case 'nursing':
                $decoratedVolunteer = new Nursing($volunteer);
                break;
            default:
                $decoratedVolunteer = $volunteer; // fallback
        }

        // Add the skill
        $decoratedVolunteer->addSkill($skillName);

        echo "Skill added successfully!";
        // Redirect back to the list
        header("Location: ?volunteer_id=$volunteerId");
    }

    private function removeSkill(): void
    {
        $volunteerId = $_GET['volunteer_id'] ?? 1;
        $skillName = $_GET['skill_name'] ?? '';

        $volunteer = new Volunteer((int)$volunteerId);
        $volunteer->removeSkill($skillName);

        echo "Skill removed!";
        header("Location: ?volunteer_id=$volunteerId");
    }
}
