<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Tests\TestCase;
use App\Models\Role;
use App\Models\School;
use App\Models\User;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        Role::insert([
            ['name' => 'school_admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'teacher', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'student', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->artisan('passport:install');

        School::factory()->create();

        Passport::actingAs(User::factory()->create([
            'role_id' => 1,
            'school_id' => 1,
        ]));
    }

    /**
     * List roles.
     *
     * @return void
     * @test
     */
    public function get_list_roles()
    {
        $this->getJson('api/v1/role')
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                ],
            ]);
    }

    /**
     * School admin create user.
     *
     * @return void
     * @test
     */
    public function school_admin_can_create_teacher()
    {
        $payload = User::factory()->make([
            'role_id' => 2,
            'school_id' => 1,
        ])->toArray();

        array_pop($payload);

        $payload['password'] = Hash::make('secret123');

        $this->postJson('api/v1/user', $payload)
            ->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'code',
            ]);
    }

    /**
     * School admin show user.
     *
     * @return void
     * @test
     */
    public function school_admin_can_view_user()
    {
        $this->getJson('api/v1/user/1')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'name',
                    'role' => ['id', 'name'],
                    'school' => ['id', 'name'],
                ]
            ]);
    }

    /**
     * School admin list user.
     *
     * @return void
     * @test
     */
    public function school_admin_can_list_created_user()
    {
        $this->getJson('api/v1/user')
            ->assertStatus(200);
    }
}
