<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        $started = microtime(true);

        fwrite(STDERR, "\n========== TEST SETUP START ==========\n");

        // Check whether Laravel has already run the test migrations.
        fwrite(
            STDERR,
            'RefreshDatabaseState::$migrated = '
                .var_export(RefreshDatabaseState::$migrated, true)
                ."\n"
        );

        fwrite(STDERR, "Calling parent::setUp()...\n");

        parent::setUp();

        fwrite(
            STDERR,
            sprintf(
                "parent::setUp() completed in %.2f seconds\n",
                microtime(true) - $started
            )
        );

        fwrite(STDERR, "========== TEST SETUP END ==========\n\n");
    }

    protected function tearDown(): void
    {
        $started = microtime(true);

        fwrite(STDERR, "\n========== TEST TEARDOWN START ==========\n");

        parent::tearDown();

        fwrite(
            STDERR,
            sprintf(
                "parent::tearDown() completed in %.2f seconds\n",
                microtime(true) - $started
            )
        );

        fwrite(STDERR, "========== TEST TEARDOWN END ==========\n\n");
    }
}
