<?php

declare(strict_types=1);

test('returns a successful response', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
});
