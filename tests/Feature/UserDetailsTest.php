<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class UserDetailsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUnauthorizeCannotViewDetails()
    {
        $response = $this->json('GET', 'api/details')
            ->assertStatus(401)
            ->assertJsonStructure([
                "error"
            ]);

        $response->assertUnauthorized();
    }

    public function testSuccessfulViewDetails()
    {
        $user = factory(User::class)->create([
            'email' => 'me@patricia.com',
            'password' => bcrypt('sample123'),
         ]);
 
 
        $loginData = ['email' => 'me@patricia.com', 'password' => 'sample123'];
 
        $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
             ->assertStatus(200)
             ->assertJsonStructure([
                 "expires_in",
                 "access_token",
                 "refresh_token"
             ]);

        $this->assertAuthenticatedAs($user);

        $this->json('GET', 'api/details')
            ->assertStatus(200)
            ->assertJsonStructure([
                "id",
                "name",
                "phone",
                "email",
                "home_address",
                "created_at",
                "updated_at"
            ]);
    }
}
