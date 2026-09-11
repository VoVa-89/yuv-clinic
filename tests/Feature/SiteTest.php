<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\ClinicSeeder::class);
    }

    public function test_home_ok(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_sitemap_xml(): void
    {
        $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function test_robots_txt(): void
    {
        $this->get('/robots.txt')->assertOk();
    }

    public function test_admin_requires_auth(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }
}
