<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Models\Role;
use App\Models\School;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        Role::insert([
            ['name' => 'school_admin', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->artisan('passport:install');

        $school = School::factory()->create();

        $this->user = User::create([
            'name' => 'teesting',
            'username' => 'usertest',
            'email' => 'usertest@email.com',
            'password' => Hash::make('secret123'),
            'role_id' => 1,
            'school_name' => $school->name,
            'school_id' => $school->id,
        ]);
    }

    /**
     * School admin login.
     *
     * @return void
     * @test
     */
    public function a_school_admin_can_login()
    {
        $payload = [
            'username' => $this->user->username,
            'school_id' => $this->user->school_id,
            'password' => 'secret123',
        ];

        $this->postJson('api/v1/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'role',
                'token',
                'code',
            ]);
    }
}
