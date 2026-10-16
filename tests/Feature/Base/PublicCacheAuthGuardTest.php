<?php

namespace Tests\Feature\Base;

use Botble\Member\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Authentication must be detected on EVERY registered guard, not just the default one.
 *
 * `Auth::guard()` consults only the default guard (`web` = admin users). The member plugin
 * registers its own `member` guard at runtime, and themes render the logged-in member's
 * name and avatar into the page - Ripple does it in partials/header.blade.php. A
 * default-guard-only check therefore marked a logged-in member's page publicly cacheable
 * and would hand their identity to every subsequent visitor.
 *
 * Exercised over HTTP so the guard is resolved the way a real request resolves it.
 */
class PublicCacheAuthGuardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['core.base.general.enable_public_cache_control' => true]);
        config(['app.debug' => true]);

        if (! class_exists(Member::class) || ! array_key_exists('member', (array) config('auth.guards'))) {
            $this->markTestSkipped('The member plugin is not active in this installation.');
        }
    }

    public function test_member_logged_in_on_the_member_guard_is_not_publicly_cached(): void
    {
        $member = new Member();
        $member->forceFill([
            'first_name' => 'Runtime',
            'last_name' => 'Gate',
            'email' => 'public-cache-auth-guard@example.com',
            'password' => bcrypt('irrelevant'),
        ]);
        $member->save();

        $response = $this->actingAs($member, 'member')->get('/');

        $response->assertOk();

        // Proves the page really is personalised, so the assertion below is guarding
        // something real rather than a hypothetical.
        $this->assertStringContainsString(
            'Runtime',
            $response->getContent(),
            'The theme is expected to render the logged-in member into the page.'
        );

        $this->assertStringNotContainsString('public', $response->headers->get('Cache-Control'));
        $this->assertSame('authenticated', $response->headers->get('X-Public-Cache-Skip'));
    }

    public function test_anonymous_visitor_is_still_publicly_cached(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $this->assertStringContainsString('public', $response->headers->get('Cache-Control'));
    }
}
