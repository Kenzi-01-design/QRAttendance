<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_login_and_claim_pages_are_accessible(): void
    {
        $this->get('/login')->assertOk();
        $this->get('/claim')->assertOk();
    }
}
