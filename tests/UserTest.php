<?php

namespace Tests;

use App\Models\Page;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

abstract class UserTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $role = "user";

    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
    }

    protected function makeRole(?string $role = null): Role
    {
        $title = $role ?: $this->role;
        return Role::query()->firstOrCreate([
            "title" => $title,
            "slug" => Str::slug($title)
        ]);
    }

    protected function makeUser(?string $role = null): User
    {
        $user = User::factory()->create();
        $user->roles()->save($this->makeRole($role));
        $user->profile()->create(Profile::factory()->raw());
        $user->wallet()->create();
        $user->page()->create(Page::factory()->raw());
        return $user;
    }

    protected function login(): void
    {
        $this->user = $this->makeUser();
        $this->be($this->user);
    }
}
