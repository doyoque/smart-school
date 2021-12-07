<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Role;

class SignupTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::insert([
            ['name' => 'school_admin', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->artisan('passport:install');
    }

    /**
     * Sign up school admin.
     *
     * @test
     * @return void
     */
    public function school_admin_can_sign_up()
    {
        $payload = [
            'name' => 'test name',
            'username' => 'testname',
            'email' => 'testing@email.com',
            'password' => Hash::make('secret123'),
            'school_name' => 'Example School',
        ];

        $this->post('api/v1/signup', $payload)
            ->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'token',
                'code',
            ]);

        $this->assertDatabaseCount('schools', 1);
        $this->assertDatabaseCount('users', 1);
    }
}
