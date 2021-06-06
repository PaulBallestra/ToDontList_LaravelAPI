<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{

    public function test_login_with_success()
    {

        $data = [
            'email' => "paul@paul.com",
            'password' => "1234"
        ];

        $response = $this->postJson('api/auth/login', $data);
        $response->assertStatus(200);

    }

    //Si pas d'input
    public function test_no_input()
    {
        $response = $this->postJson('api/auth/login');
        $response->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    //Si pas les bonnes valeurs (email incomplet ...)
    public function test_invalid_input()
    {
        $data = [
            'email' => "polpol.com",
            'password' => "1234",
        ];

        $response = $this->postJson('api/auth/login', $data);
        $response->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    //Si pas les bons credentials
    public function test_invalid_credentials()
    {
        $data = [
            'email' => "pol@pol.com",
            'password' => "azertyuiop",
        ];


        $response = $this->postJson('api/auth/login', $data);
        $response->assertStatus(401)
            ->assertJsonStructure(['errors']);

    }

}
