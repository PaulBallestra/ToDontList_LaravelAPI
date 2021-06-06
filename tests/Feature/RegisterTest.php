<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    //Pas d'input 422
    public function test_no_input()
    {
        $response = $this->postJson('api/auth/register');
        $response->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    //Si pas les bonnes valeurs (email incomplet ...) 422
    public function test_invalid_input()
    {
        $data = [
            'email' => "polpol.com",
            'password' => "1234",
            'name' => "Name"
        ];

        $response = $this->postJson('api/auth/register', $data);
        $response->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    //Compte déjà créé 409
    public function test_already_created_credentials()
    {
        $data = [
            'email' => "pol@pol.com",
            'password' => "azertyuiop",
            'name' => 'Name'
        ];


        $response = $this->postJson('api/auth/register', $data);
        $response->assertStatus(409)
            ->assertJsonStructure(['errors']);

    }

    //Si il créé son compte sans problèmes
    public function test_register_with_success()
    {

        $data = [
            'email' => "new@email.com",
            'password' => "1234",
            'name' => 'Name'
        ];

        $response = $this->postJson('api/auth/register', $data);
        $response->assertStatus(201)
            ->assertJsonStructure(['token']);

    }

}
