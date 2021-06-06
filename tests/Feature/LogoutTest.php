<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{

    //Si il se déconnecte sans problèmes
    public function test_logout_with_success()
    {
        $response = $this->postJson('api/auth/logout');
        $response->assertStatus(204);
    }
}
