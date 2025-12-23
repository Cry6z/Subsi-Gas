<?php

use App\Models\Location;
use App\Models\User;

test('public locations api returns locations', function () {
    $distributor = User::factory()->create([
        'role' => 'distributor',
        'is_active' => true,
    ]);

    Location::factory()->create([
        'distributor_id' => $distributor->id,
        'latitude' => -6.2,
        'longitude' => 106.816666,
        'stock' => 10,
    ]);

    $response = $this->getJson('/api/locations');

    $response->assertOk();
    $response->assertJsonStructure([
        'data',
        'links',
        'meta',
    ]);
});
