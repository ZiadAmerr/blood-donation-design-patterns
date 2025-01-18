<?php
require_once __DIR__ . '/../models/people/Volunteer.php';

class VolunteerController
{
    public function handleRequest(): void
    {
        // Decide action from the query parameter: e.g. ?action=list
        $action = $_GET['action'] ?? 'list';

        switch ($action) {
            case 'create':
                $this->create();
                break;

            case 'store':
                $this->store();
                break;

            case 'edit':
                $this->edit();
                break;

            case 'update':
                $this->update();
                break;

            case 'delete':
                $this->delete();
                break;

            case 'add_skill':
                $this->addSkill();
                break;

            case 'remove_skill':
                $this->removeSkill();
                break;

            case 'list':
            default:
                $this->index();
                break;
        }
    }

    /**
     * 1) Show a list of all volunteers
     */
    private function index(): void
    {
        // You can use a static method in Volunteer to fetch them all:
        $volunteers = Volunteer::allVolunteers();

        // Load a view file, e.g. views/volunteers/index.php
        require __DIR__ . '/../views/volunteers/index.php';
    }

    /**
     * 2) Show the create volunteer form
     */
    private function create(): void
    {
        // Show a form to create a volunteer
        require __DIR__ . '/../views/volunteers/create.php';
    }

    /**
     * 3) Handle the create volunteer form submission
     */
    private function store(): void
    {
        // Grab form inputs (from $_POST)
        $name       = $_POST['name'] ?? '';
        $dob        = $_POST['dob'] ?? '';
        $nationalId = $_POST['national_id'] ?? '';
        $addressId  = (int)($_POST['address_id'] ?? 0);   // or store as string if your DB uses text
        $phone      = $_POST['phone'] ?? '';

        // We don’t see a direct “createVolunteer” in your code snippet,
        // but you can adapt from Donor’s “create” method:
        $personId = Donor::create($name, $dob, $nationalId, (string)$addressId, $phone);
        if (!$personId) {
            echo "Error: could not create Donor record.";
            return;
        }

        // Now we have a new Donor -> we treat that as Volunteer
        $volunteer = new Volunteer($personId);

        // Optional: set isAvailable to true, or other fields
        // $volunteer->isAvailable = true;

        // redirect to the list
        header("Location: ?action=list");
        exit;
    }

    /**
     * 4) Show the edit form for an existing volunteer
     */
    private function edit(): void
    {
        $personId = (int)($_GET['volunteer_id'] ?? 0);

        // Instantiate the existing volunteer
        $volunteer = new Volunteer($personId);

        // Show a form (similar to create form) but with data pre-filled
        require __DIR__ . '/../views/volunteers/edit.php';
    }

    /**
     * 5) Process the edit form
     */
    private function update(): void
    {
        $personId   = (int)($_POST['volunteer_id'] ?? 0);
        $volunteer  = new Volunteer($personId);

        // Possibly update name, phone, or other fields
        $newName    = $_POST['name'] ?? $volunteer->getName();
        $newDOB     = $_POST['dob'] ?? $volunteer->getDateOfBirth();
        $newNatId   = $_POST['national_id'] ?? $volunteer->getNationalId();
        $newPhone   = $_POST['phone'] ?? $volunteer->getPhoneNumber();
        // or handle isAvailable, etc.

        // To update the Donor record in DB:
        static::executeUpdate(
            "UPDATE Donor 
             SET name = ?, date_of_birth = ?, national_id = ?, phone_number = ? 
             WHERE person_id = ?",
            "ssssi",
            $newName,
            $newDOB,
            $newNatId,
            $newPhone,
            $personId
        );

        // or if you have a method in Donor / Volunteer to do that
        // $volunteer->updateSomething(...);

        // redirect back to list
        header("Location: ?action=list");
        exit;
    }

    /**
     * 6) Delete a volunteer
     */
    private function delete(): void
    {
        $personId = (int)($_GET['volunteer_id'] ?? 0);

        // Instantiating volunteer calls the model constructor
        $volunteer = new Volunteer($personId);

        // Call the model’s delete() (which deletes skills, tasks, donor/person)
        $volunteer->delete();

        header("Location: ?action=list");
        exit;
    }

    /**
     * 7) Add skill to a volunteer
     */
    private function addSkill(): void
    {
        $personId = (int)($_GET['volunteer_id'] ?? 0);
        $skill    = $_POST['skill'] ?? '';

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
            break;
        }
        
        if ($skill) {
            $volunteer = new Volunteer($personId);
            $volunteer->addSkill($skill);
        }

        // Return to the edit page
        header("Location: ?action=edit&volunteer_id={$personId}");
        exit;
    }

    /**
     * 8) Remove a skill
     */
    private function removeSkill(): void
    {
        $personId = (int)($_GET['volunteer_id'] ?? 0);
        $skill    = $_GET['skill'] ?? '';

        $volunteer = new Volunteer((int)$volunteerId);
        $volunteer->removeSkill($skillName);
        
        if ($skill) {
            // Easiest way is to remove from DB, then reload from constructor
            static::executeUpdate(
                "DELETE FROM VolunteerSkills WHERE person_id = ? AND skill = ?",
                "is",
                $personId,
                $skill
            );
        }

        // Return to the edit page
        header("Location: ?action=edit&volunteer_id={$personId}");
        exit;
    }

    /**
     * If you want to re-use the same DB logic as the Volunteer model, 
     * you can do so, but you must either:
     *  - Inherit from the same Model trait, or
     *  - Directly call Volunteer’s methods.
     */
    private static function executeUpdate(string $sql, string $types = "", ...$params): int
    {
        return Volunteer::executeUpdate($sql, $types, ...$params);
    }
}
