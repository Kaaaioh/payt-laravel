<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;


class RedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_is_creating()
    {
        $response = $this->postJson('/api/redirects', [
            'url_destino' => 'https://github.com',
        ]);

        $response->assertStatus(201);
    }

    public function test_if_is_blocking_wrong_url()
    {
        $response = $this->postJson('/api/redirects', [
            'url_destino' => 'https://googlaadasddse.com',
        ]);

        $response->assertStatus(422);
    }

    public function test_if_is_blocking_wrong_dns()
    {
        $response = $this->postJson('/api/redirects', [
            'url_destino' => 'http://googlase.com',
        ]);

        $response->assertStatus(422);
    }
}
