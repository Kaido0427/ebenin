<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_admin_login_page_is_reachable_on_main_domain(): void
    {
        $response = $this->get('https://e-benin.com/admin/login');

        $response->assertStatus(200);
    }
}
