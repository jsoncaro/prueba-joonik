<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_uses_the_correct_traits()
    {
        $this->assertContains(HasFactory::class, class_uses(User::class));
        $this->assertContains(Notifiable::class, class_uses(User::class));
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $user = new User();

        $this->assertEquals([
            'name',
            'email',
            'password',
        ], $user->getFillable());
    }

    /** @test */
    public function it_has_correct_hidden_attributes()
    {
        $user = new User();

        $this->assertEquals([
            'password',
            'remember_token',
        ], $user->getHidden());
    }

    /** @test */
    public function it_has_correct_casts()
    {
        $user = new User();

        $casts = $user->getCasts();

        $this->assertEquals('datetime', $casts['email_verified_at']);
        $this->assertEquals('hashed', $casts['password']);
    }

    /** @test */
    public function it_can_be_created_with_fillable_attributes()
    {
        $data = [
            'name'     => 'John Doe',
            'email'    => 'john@example.com',
            'password' => bcrypt('secret'),
        ];

        $user = User::create($data);

        $this->assertDatabaseHas('users', [
            'name'  => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    /** @test */
    public function it_can_be_updated()
    {
        $user = User::factory()->create();

        $user->update(['name' => 'Jane Doe']);

        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'name' => 'Jane Doe',
        ]);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $user = User::factory()->create();

        $user->delete();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
