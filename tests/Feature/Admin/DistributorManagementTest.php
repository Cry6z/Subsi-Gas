<?php

use App\Livewire\Admin\DistributorCreate;
use App\Livewire\Admin\DistributorList;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

test('admin can create distributor and send password reset invite', function () {
    Notification::fake();

    $admin = User::factory()->create([
        'role' => 'admin',
        'is_active' => true,
    ]);

    $this->actingAs($admin);

    Livewire::test(DistributorCreate::class)
        ->set('name', 'Distributor A')
        ->set('email', 'distributor.a@example.com')
        ->set('phone', '08123456789')
        ->call('create')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.distributors', absolute: false));

    $created = User::query()->where('email', 'distributor.a@example.com')->first();

    expect($created)->not->toBeNull();
    expect($created->role)->toBe('distributor');
    expect($created->created_by_admin_id)->toBe($admin->id);
    expect($created->is_active)->toBeTrue();

    Notification::assertSentTo($created, ResetPassword::class);
});

test('non-admin cannot access admin distributor routes', function () {
    $distributor = User::factory()->create([
        'role' => 'distributor',
        'is_active' => true,
    ]);

    $this->actingAs($distributor);

    $this->get(route('admin.distributors'))->assertStatus(403);
    $this->get(route('admin.distributors.create'))->assertStatus(403);
});

test('admin can deactivate distributor from distributor list', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'is_active' => true,
    ]);

    $distributor = User::factory()->create([
        'role' => 'distributor',
        'is_active' => true,
        'created_by_admin_id' => $admin->id,
    ]);

    $this->actingAs($admin);

    Livewire::test(DistributorList::class)
        ->call('toggleActive', $distributor->id)
        ->assertHasNoErrors();

    expect($distributor->refresh()->is_active)->toBeFalse();
});
