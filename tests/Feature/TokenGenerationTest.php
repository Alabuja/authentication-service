<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class TokenGenerationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUnauthorizeRefreshToken()
    {
        $token = str_random(10);
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('POST', '/api/refreshtoken', ['RefreshToken' => $token]);
        
        $response->assertStatus(401)
            ->assertJsonStructure([
                'error',
            ]);

        $response->assertUnauthorized();
    }

    public function testSuccessfulRefreshToken()
    {
        $user = factory(User::class)->create([
            'email' => 'alabuja@patricia.com',
            'password' => bcrypt('sample123'),
         ]);
 
 
        $loginData = ['email' => 'alabuja@patricia.com', 'password' => 'sample123'];
 
        $response = $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
             ->assertStatus(200)
             ->assertJsonStructure([
                 "expires_in",
                 "access_token",
                 "refresh_token"
             ]);

        $this->assertAuthenticatedAs($user);

        $token = $response['refresh_token'];

        $data = [
            "Refreshtoken" => $token
        ];

        $this->json('POST', 'api/refreshtoken', $data, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "token_type",
                "expires_in",
                "access_token",
                "refresh_token"
            ]);
    }
}
