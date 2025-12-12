<?php

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('admin can authenticate using admin guard', function () {
    /** @var \Tests\TestCase $this */
    /** @var Admin $admin */
    $admin = Admin::factory()->create([
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/admin/login', [
        'email' => 'admin@test.com',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('admin.dashboard.redirect'));
    expect(Auth::guard('admin')->check())->toBeTrue();
    expect(Auth::guard('admin')->user()?->id)->toBe($admin->id);
});

test('user can authenticate using web guard', function () {
    /** @var User $user */
    $user = User::factory()->create([
        'email' => 'user@test.com',
        'password' => bcrypt('password'),
    ]);

    // For a simpler test, we can just check the guard directly
    Auth::guard('web')->login($user);

    expect(Auth::guard('web')->check())->toBeTrue();
    expect(Auth::guard('web')->user()?->id)->toBe($user->id);
});

test('admin and user guards are independent', function () {
    /** @var Admin $admin */
    $admin = Admin::factory()->create([
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
    ]);

    /** @var User $user */
    $user = User::factory()->create([
        'email' => 'user@test.com',
        'password' => bcrypt('password'),
    ]);

    // Login as admin
    Auth::guard('admin')->login($admin);

    expect(Auth::guard('admin')->check())->toBeTrue();
    expect(Auth::guard('web')->check())->toBeFalse();

    // Login as user
    Auth::guard('web')->login($user);

    expect(Auth::guard('web')->check())->toBeTrue();
    expect(Auth::guard('admin')->check())->toBeTrue(); // Admin is still logged in

    // Logout admin
    Auth::guard('admin')->logout();

    expect(Auth::guard('admin')->check())->toBeFalse();
    expect(Auth::guard('web')->check())->toBeTrue(); // User is still logged in
});

test('admin cannot access user routes without web guard', function () {
    /** @var \Tests\TestCase $this */
    /** @var Admin $admin */
    $admin = Admin::factory()->create([
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
    ]);

    Auth::guard('admin')->login($admin);

    $response = $this->get('/dashboard');

    // Admin is not authenticated with web guard, so should be redirected
    $response->assertRedirect('/login');
});

test('user cannot access admin routes without admin guard', function () {
    /** @var \Tests\TestCase $this */
    /** @var User $user */
    $user = User::factory()->create([
        'email' => 'user@test.com',
        'password' => bcrypt('password'),
    ]);

    Auth::guard('web')->login($user);

    $response = $this->get('/admin/dashboard');

    // User is not authenticated with admin guard, so should be redirected
    $response->assertRedirect('/admin/login');
});

test('admin helper methods work correctly', function () {
    /** @var Admin $admin */
    $admin = Admin::factory()->create();

    expect(Admin::isAuthenticated())->toBeFalse();

    Auth::guard('admin')->login($admin);

    expect(Admin::isAuthenticated())->toBeTrue();
    expect(Admin::current()?->id)->toBe($admin->id);
    expect($admin->getGuardName())->toBe('admin');
});
