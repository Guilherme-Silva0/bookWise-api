<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_endpoint(): void
    {
        $user = User::factory()->makeOne();

        $response = $this->postJson('/api/user/register?lang=pt_BR', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        
        $response->assertStatus(201);
        $response->assertJsonStructure(['token']);
        $response->assertJson(fn (AssertableJson $json) => $json->whereType('token', 'string')->etc());
    }

    public function test_validate_user_registration_empty_fields(): void
    {
        $response = $this->postJson('/api/user/register?lang=pt_BR', [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(422);

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors']);

            $json->whereAll([
                'message' => 'O campo primeiro nome é obrigatório. (e mais 3 erros)',
                'errors.first_name.0' => 'O campo primeiro nome é obrigatório.',
                'errors.last_name.0' => 'O campo sobrenome é obrigatório.',
                'errors.email.0' => 'O campo email é obrigatório.',
                'errors.password.0' => 'O campo senha é obrigatório.',
            ]);
        });
    }

    public function test_validate_user_registration_invalid_fields(): void
    {
        $response = $this->postJson('/api/user/register?lang=pt_BR', [
            'first_name' => 'J',
            'last_name' => 'D',
            'email' => 'j',
            'password' => 'p',
            'password_confirmation' => 'j',
        ]);
    
        $response->assertStatus(422);

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors']);

            $json->whereAll([
                'message' => 'O campo primeiro nome deve ter pelo menos 2 caracteres. (e mais 4 erros)',
                'errors.first_name.0' => 'O campo primeiro nome deve ter pelo menos 2 caracteres.',
                'errors.last_name.0' => 'O campo sobrenome deve ter pelo menos 2 caracteres.',
                'errors.email.0' => 'O campo email deve ser um endereço de e-mail válido.',
                'errors.password.0' => 'O campo senha deve ter pelo menos 8 caracteres.',
                'errors.password.1' => 'O campo senha de confirmação não confere.',
            ]);
        });
    }

    public function test_get_user_endpoint_with_token(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson('/api/user?lang=pt_BR', [
            'Authorization' => 'Bearer ' . $user->createToken('authToken')->plainTextToken
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($user) {
            $json->hasAll(['id', 'first_name', 'last_name', 'email', 'profile_image', 'profile_info', 'email_verified_at', 'user_type', 'status', 'created_at', 'updated_at']);

            $json->whereAll([
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'profile_image' => $user->profile_image,
                'profile_info' => $user->profile_info,
                'email_verified_at' => $user->email_verified_at,
                'user_type' => 'normal',
                'status' => 'active',
            ]);
        });
    }

    public function test_get_user_endpoint_no_token(): void
    {
        $response = $this->getJson('/api/user?lang=pt_BR');

        $response->assertStatus(401);

        $response->assertJson(fn (AssertableJson $json) => $json->where('message', 'Unauthenticated.')->etc());
    
        $response->assertJsonMissing(['id', 'first_name', 'last_name', 'email', 'profile_image', 'profile_info', 'email_verified_at', 'user_type', 'status', 'created_at', 'updated_at']);
    }

    public function test_get_user_endpoint_invalid_token(): void
    {
        $response = $this->getJson('/api/user?lang=pt_BR', [
            'Authorization' => 'Bearer invalid-token'
        ]);

        $response->assertStatus(401);

        $response->assertJson(fn (AssertableJson $json) => $json->where('message', 'Unauthenticated.')->etc());

        $response->assertJsonMissing(['id', 'first_name', 'last_name', 'email', 'profile_image', 'profile_info', 'email_verified_at', 'user_type', 'status', 'created_at', 'updated_at']);

    }

    public function test_login_user_endpoint(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/user/login?lang=pt_BR', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
        $response->assertJson(fn (AssertableJson $json) => $json->whereType('token', 'string')->etc());
    }

    public function test_login_user_endpoint_invalid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/user/login?lang=pt_BR', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(401);

        $response->assertJson(fn (AssertableJson $json) => $json->where('message', 'Credenciais inválidas.'));
        
        $response->assertJsonMissing(['id', 'first_name', 'last_name', 'email', 'profile_image', 'profile_info', 'email_verified_at', 'user_type', 'status', 'created_at', 'updated_at']);
    }

    public function test_logout_user_endpoint(): void
    {

        $user = User::factory()->create();
        $token = $user->createToken('authToken')->plainTextToken;

        $response = $this->postJson('/api/user/logout?lang=pt_BR', [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(204);
    }

    public function test_logout_user_endpoint_invalid_token(): void
    {
        $response = $this->postJson('/api/user/logout?lang=pt_BR', [], [
            'Authorization' => 'Bearer invalid-token'
        ]);

        $response->assertStatus(401);

        $response->assertJson(fn (AssertableJson $json) => $json->where('message', 'Unauthenticated.')->etc());
    }
}
