<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouterApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_login_and_manage_routers(): void
    {
        $registerResponse = $this->postJson('/api/register', [
            'name' => 'Ana García',
            'email' => 'ana@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $registerResponse->assertStatus(201)
            ->assertJsonPath('user.email', 'ana@example.com');

        $token = $registerResponse->json('token');
        $this->assertNotEmpty($token);

        $createResponse = $this->withToken($token)->postJson('/api/routers', [
            'name' => 'Router principal',
            'brand' => 'TP-Link',
            'model' => 'Archer C7',
            'ip_address' => '192.168.1.1',
            'mac_address' => 'AA:BB:CC:DD:EE:FF',
            'ssid' => 'MiRed',
            'password' => 'secreto123',
            'status' => 'activo',
            'location' => 'Sala',
            'notes' => 'Router de prueba',
        ]);

        $createResponse->assertStatus(201)
            ->assertJsonPath('data.name', 'Router principal');

        $routerId = $createResponse->json('data.id');

        $this->withToken($token)->getJson('/api/routers')
            ->assertOk()
            ->assertJsonFragment(['name' => 'Router principal']);

        $this->withToken($token)->putJson('/api/routers/' . $routerId, [
            'name' => 'Router principal actualizado',
            'brand' => 'Huawei',
            'status' => 'mantenimiento',
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Router principal actualizado');

        $this->withToken($token)->deleteJson('/api/routers/' . $routerId)
            ->assertOk();
    }
}
