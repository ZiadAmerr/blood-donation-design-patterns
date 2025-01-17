<?php
// File: /controllers/VolunteerController.php

require_once __DIR__ . '/../models/people/Volunteer.php';

class VolunteerController
{
    public function handleRequest(): void
    {
        // action is decided from URL: e.g. ?action=list
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
            default:
                $this->index();
                break;
        }
    }

    // 1) List all volunteers
    private function index(): void
    {
        $volunteers = Volunteer::allVolunteers();
        require __DIR__ . '/../views/volunteers/index.php';
    }

    // 2) Show create form
    private function create(): void
    {
        require __DIR__ . '/../views/volunteers/create.php';
    }

    // 3) Process create form (store)
    private function store(): void
    {
        $name       = $_POST['name'] ?? '';
        $dob        = $_POST['dob'] ?? '';
        $nationalId = $_POST['national_id'] ?? '';
        $addressId  = (int)($_POST['address_id'] ?? 0);
        $phone      = $_POST['phone_number'] ?? '';

        $volunteer = Volunteer::createVolunteer($name, $dob, $nationalId, $addressId, $phone);
        if ($volunteer) {
            // redirect to volunteer list
            header("Location: ?action=list");
            exit;
        } else {
            echo "Error creating volunteer.";
        }
    }

    // 4) Show edit form
    private function edit(): void
    {
        $volunteerId = (int)($_GET['volunteer_id'] ?? 0);
        $volunteer = new Volunteer($volunteerId);
        require __DIR__ . '/../views/volunteers/edit.php';
    }

    // 5) Process edit form (update)
    private function update(): void
    {
        $volunteerId = (int)($_POST['volunteer_id'] ?? 0);
        $volunteer = new Volunteer($volunteerId);

        $name       = $_POST['name'] ?? $volunteer->getName();
        $phone      = $_POST['phone_number'] ?? $volunteer->getPhoneNumber();
        $isAvailable = isset($_POST['isAvailable']) ? true : false;

        $volunteer->updateVolunteer($name, $phone, $isAvailable);

        header("Location: ?action=list");
        exit;
    }

    // 6) Delete volunteer
    private function delete(): void
    {
        $volunteerId = (int)($_GET['volunteer_id'] ?? 0);
        $volunteer = new Volunteer($volunteerId);
        $volunteer->deleteVolunteer();

        header("Location: ?action=list");
        exit;
    }

    // 7) Add skill
    private function addSkill(): void
    {
        $volunteerId = (int)($_GET['volunteer_id'] ?? 0);
        $skillName   = $_POST['skill_name'] ?? '';
        if ($skillName) {
            $volunteer = new Volunteer($volunteerId);
            $volunteer->addSkill($skillName);
        }
        header("Location: ?action=edit&volunteer_id={$volunteerId}");
        exit;
    }

    // 8) Remove skill
    private function removeSkill(): void
    {
        $volunteerId = (int)($_GET['volunteer_id'] ?? 0);
        $skillName   = $_GET['skill_name'] ?? '';
        if ($skillName) {
            $volunteer = new Volunteer($volunteerId);
            $volunteer->removeSkill($skillName);
        }
        header("Location: ?action=edit&volunteer_id={$volunteerId}");
        exit;
    }
}
