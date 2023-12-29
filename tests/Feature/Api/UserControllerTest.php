<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_endpoint(): void
    {
        $user = User::factory()->makeOne();

        $response = $this->postJson('/api/user/register', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(201);
        
        $response->assertJson(function (AssertableJson $json) use ($user) {
            $json->hasAll([
                'first_name',
                'last_name',
                'email',
                'id',
                'updated_at',
                'created_at',
            ]);

            $json->whereAll([
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
            ])->etc();
        });
    }
}
