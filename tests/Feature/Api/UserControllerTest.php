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

        $response = $this->postJson('/api/user/register?lang=pt_BR', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
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
}
