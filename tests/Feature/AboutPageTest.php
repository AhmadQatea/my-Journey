<?php

use App\Models\Admin;

it('shows active admins on about page', function () {
    $admins = Admin::factory()->count(3)->create();

    $response = $this->get('/about');

    $response->assertSuccessful();

    foreach ($admins as $admin) {
        $response->assertSeeText($admin->name);
    }
});
