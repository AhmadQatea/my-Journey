<?php

use App\Models\Governorate;

it('paginates governorates on provinces page', function () {
    Governorate::factory()->count(15)->create();

    $response = $this->get('/provinces');

    $response->assertSuccessful();
    $response->assertSeeText('المحافظات السورية');

    // Laravel's default paginator should render a link to the second page
    $response->assertSee('?page=2');
});
