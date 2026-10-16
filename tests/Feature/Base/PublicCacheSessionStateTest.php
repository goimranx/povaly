<?php

namespace Tests\Feature\Base;

use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

/**
 * The session-state gate, exercised through the real HTTP stack.
 *
 * These MUST go through $this->get() rather than calling the middleware directly. The gate
 * was once written with an isStarted() check, which unit tests satisfied by building a
 * Store by hand and calling start() on it. On a real request StartSession saves and closes
 * the session before the response returns, so Store::$started is always false by the time
 * RequestHandled fires - the gate never fired in production, and every hand-rolled unit
 * test still passed. Only a request through the kernel catches that.
 *
 * Every test asserts a 200 first: a 404 or 500 would bail at `unsuccessful-status` and
 * make these look like they were exercising the session gate when they were not.
 *
 * Note the split between withSession() and a real flash(). withSession() sets a PERSISTENT
 * key, which survives to RequestHandled. A real flash does NOT: StartSession ages the flash
 * bag and saves the session before the response returns, so a flash consumed during
 * rendering is already forgotten by the time the middleware looks. Only the rendered body
 * still shows it. Tests written with withSession() alone therefore pass while the case they
 * were meant to guard - the one a redirect actually produces - goes uncaught.
 *
 * The real-flash case is therefore covered by the body-marker gate instead, in
 * Botble\Base\Tests\Feature\PublicCacheControlTest. Driving a genuine flash from here is
 * not deterministic: with SESSION_DRIVER=array the session the test seeds is not reliably
 * the session the request reads, so such a test passes or fails depending on ordering.
 */
class PublicCacheSessionStateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['core.base.general.enable_public_cache_control' => true]);
        config(['core.base.general.public_cache_max_age' => 600]);
        config(['app.debug' => true]);
    }

    public function test_anonymous_request_is_publicly_cached(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $this->assertStringContainsString('public', $response->headers->get('Cache-Control'));
    }

    public function test_flash_message_in_session_is_not_publicly_cached(): void
    {
        $response = $this->withSession(['success_msg' => 'Your message has been sent'])->get('/');

        $response->assertOk();
        $this->assertStringNotContainsString('public', $response->headers->get('Cache-Control'));
        $this->assertSame('session-state', $response->headers->get('X-Public-Cache-Skip'));
    }

    public function test_error_message_in_session_is_not_publicly_cached(): void
    {
        $response = $this->withSession(['error_msg' => 'Something went wrong'])->get('/');

        $response->assertOk();
        $this->assertSame('session-state', $response->headers->get('X-Public-Cache-Skip'));
    }

    public function test_validation_error_bag_is_not_publicly_cached(): void
    {
        $errors = new ViewErrorBag();
        $errors->put('default', new MessageBag(['email' => ['The email is invalid.']]));

        $response = $this->withSession(['errors' => $errors])->get('/');

        $response->assertOk();
        $this->assertSame('session-state', $response->headers->get('X-Public-Cache-Skip'));
    }

    public function test_language_keys_written_on_every_request_stay_inert(): void
    {
        $response = $this->withSession(['language' => 'en', 'previous_language' => 'vi'])->get('/');

        $response->assertOk();

        // The language plugin writes these on every request; treating them as
        // personalising would disable public caching for everyone.
        $this->assertStringContainsString('public', $response->headers->get('Cache-Control'));
    }
}
