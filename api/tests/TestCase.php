<?php

use \Nuwave\Lighthouse\Testing\MakesGraphQLRequestsLumen;
use Nuwave\Lighthouse\Testing\ClearsSchemaCache;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use MakesGraphQLRequestsLumen;
    use ClearsSchemaCache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootClearsSchemaCache();
    }
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}
