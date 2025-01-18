<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/blood_donations/BloodDonation.php";

class BloodDonationService {

    /**
     * Parse the value between <eligibility> and </eligibility> tags.
     *
     * @param string $xml_ret The XML string to parse.
     * @return string|null The value between the <eligibility> tags, or null if not found.
     */
    public function checkEligibility(string $xml_ret): ?string {
        // Use regular expression to extract content between <eligibility> and </eligibility>
        preg_match('/<eligibility>(.*?)<\/eligibility>/', $xml_ret, $matches);

        // Check if the match was successful
        if (isset($matches[1])) {
            if ($matches[1] === "Eligible") {
                return true;
            } else {
                return false;
            }
        } else {
            return false; // Return null if the <eligibility> tag is not found
        }
    }

}
?>
