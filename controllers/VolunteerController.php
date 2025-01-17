<?php
require_once __DIR__ . '/../models/people/Volunteer.php';
require_once __DIR__ . '/../models/skills/Driving.php';
require_once __DIR__ . '/../models/skills/Nursing.php';
// ... any other skill classes if you have them

class VolunteerController
{
    public function handleRequest(): void
    {
        $action = $_GET['action'] ?? 'list';

        switch ($action) {
            case 'create':
                $this->createVolunteer();
                break;

            case 'store':
                $this->storeVolunteer();
                break;

            case 'edit':
                $this->editVolunteer();
                break;

            case 'update':
                $this->updateVolunteer();
                break;

            case 'delete':
                $this->deleteVolunteer();
                break;

            case 'add_skill':
                $this->addSkill();
                break;

            case 'remove_skill':
                $this->removeSkill();
                break;

            default:
                $this->listVolunteers();
                break;
        }
    }

    /**
     * Show form to create a new volunteer
     */
    private function createVolunteer(): void
    {
        include __DIR__ . '/../views/volunteers/create.php';
    }

    /**
     * Store new volunteer in DB
     */
    private function storeVolunteer(): void
    {
        // Typically from $_POST
        $name       = $_POST['name'] ?? '';
        $dob        = $_POST['dob'] ?? '';
        $nationalId = $_POST['national_id'] ?? '';
        $addressId  = (int)($_POST['address_id'] ?? 0);
        $phone      = $_POST['phone_number'] ?? '';

        $volunteer = Volunteer::createVolunteer($name, $dob, $nationalId, $addressId, $phone);

        if ($volunteer) {
            // redirect or show success
            header('Location: ?action=list');
            exit;
        } else {
            echo "Error creating volunteer.";
        }
    }

    /**
     * List all volunteers
     */
    private function listVolunteers(): void
    {
        // Here, you'd fetch all volunteers from DB. Because we don’t have a direct "Volunteer::all()" yet,
        // we might do something like fetch all Donors with a flag that are volunteers, or just fetch from Person table, etc.
        // Example:
        $rows = Model::fetchAll("SELECT person_id FROM Donor");
        $volunteers = [];
        foreach ($rows as $row) {
            try {
                $volunteers[] = new Volunteer($row['person_id']);
            } catch (Exception $e) {
                // handle error
            }
        }

        include __DIR__ . '/../views/volunteers/index.php';
    }

    /**
     * Show form to edit an existing volunteer
     */
    private function editVolunteer(): void
    {
        $volunteerId = (int)($_GET['volunteer_id'] ?? 0);
        $volunteer = new Volunteer($volunteerId);
        include __DIR__ . '/../views/volunteers/edit.php';
    }

    /**
     * Update volunteer in DB
     */
    private function updateVolunteer(): void
    {
        $volunteerId = (int)($_POST['volunteer_id'] ?? 0);
        $volunteer = new Volunteer($volunteerId);

        $name       = $_POST['name'] ?? $volunteer->getName();
        $phone      = $_POST['phone_number'] ?? $volunteer->getPhoneNumber();
        $available  = isset($_POST['isAvailable']) ? true : false;

        $volunteer->updateVolunteer($name, $phone, $available);

        header('Location: ?action=list');
        exit;
    }

    /**
     * Delete volunteer from DB
     */
    private function deleteVolunteer(): void
    {
        $volunteerId = (int)($_GET['volunteer_id'] ?? 0);
        $volunteer = new Volunteer($volunteerId);
        $volunteer->deleteVolunteer();
        header('Location: ?action=list');
        exit;
    }

    /**
     * Add skill to volunteer
     */
    private function addSkill(): void
    {
        $volunteerId = (int)($_GET['volunteer_id'] ?? 0);
        $skillName   = $_POST['skill_name'] ?? '';
        $volunteer = new Volunteer($volunteerId);

        if ($skillName) {
            $volunteer->addSkill($skillName);
        }
        header("Location: ?action=edit&volunteer_id={$volunteerId}");
        exit;
    }

    /**
     * Remove a skill from a volunteer
     */
    private function removeSkill(): void
    {
        $volunteerId = (int)($_GET['volunteer_id'] ?? 0);
        $skillName   = $_GET['skill_name'] ?? '';
        $volunteer = new Volunteer($volunteerId);

        if ($skillName) {
            $volunteer->removeSkill($skillName);
        }
        header("Location: ?action=edit&volunteer_id={$volunteerId}");
        exit;
    }
}
