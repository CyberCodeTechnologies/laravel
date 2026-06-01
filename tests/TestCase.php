<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Additional setup if needed
    }

    /**
     * Tear down the test environment.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Additional cleanup if needed
    }

    /**
     * Act as an authenticated admin user.
     */
    protected function actAsAdmin()
    {
        $admin = \App\Models\User::factory()->create([
            'role' => 'admin',
            'is_approved' => true,
        ]);
        
        return $this->actingAs($admin);
    }

    /**
     * Act as an authenticated artist user.
     */
    protected function actAsArtist()
    {
        $artist = \App\Models\User::factory()->create([
            'role' => 'artist',
            'is_approved' => true,
        ]);
        
        return $this->actingAs($artist);
    }

    /**
     * Act as an authenticated collector user.
     */
    protected function actAsCollector()
    {
        $collector = \App\Models\User::factory()->create([
            'role' => 'collector',
            'is_approved' => true,
        ]);
        
        return $this->actingAs($collector);
    }
}
