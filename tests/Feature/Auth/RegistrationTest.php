<?php

test('registration routes are disabled', function () {
    $this->get('/register')->assertStatus(404);

    $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertStatus(404);
});