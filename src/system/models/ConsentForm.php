<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

// /model/ConsentForm.php
class ConsentForm {
    public $patient_name;
    public $dob;
    public $mrn;
    public $gender;
    public $email;
    public $signature;
    public $contact_before;
    public $patient_use_agreed;
    public $consent_decision;
    public $consent_agreed;

    public function __construct(
        $patient_name = '',
        $dob = '',
        $mrn = '',
        $gender = '',
        $email = '',
        $signature = '',
        $contact_before = '',
        $patient_use_agreed = '',
        $consent_decision = '',
        $consent_agreed = 0
    ) {
        $this->patient_name = $patient_name;
        $this->dob = $dob;
        $this->mrn = $mrn;
        $this->gender = $gender;
        $this->email = $email;
        $this->signature = $signature;
        $this->contact_before = $contact_before;
        $this->patient_use_agreed = $patient_use_agreed;
        $this->consent_decision = $consent_decision;
        $this->consent_agreed = $consent_agreed;
    }

}