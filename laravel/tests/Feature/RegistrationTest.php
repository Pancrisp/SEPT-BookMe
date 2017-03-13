<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegistrationTest extends TestCase
{
    public function testUniqueUsername()
    {
        $registrationController = new RegistrationController();

        $this->assertTrue($registrationController->isUniqueUsername('gracezzz'));
        $this->assertFalse($registrationController->isUniqueUsername('grace'));
    }

    public function testValidDetails()
    {
        $registrationController = new RegistrationController();

        $newUser = [
            'name'      => 'Grace',
            'username'  => 'gracezzz',
            'password'  => 'password',
            'address'   => '',
            'mobile_no' => '11111'
        ];

        $this->assertFalse($registrationController->isValidated($newUser));
    }

    public function testRegistration()
    {
        $registrationController = new RegistrationController();

        $newUser = [
            'name'      => 'Grace',
            'username'  => 'gracezzz',
            'password'  => 'password',
            'address'   => '123 Sunshine St, East Melbourne, VIC3111',
            'mobile_no' => '0422333444'
        ];

        $this->assertTrue($registrationController->register($newUser));

    }
}
