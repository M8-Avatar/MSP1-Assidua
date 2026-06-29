<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /** @test */
    public function the_application_root_redirects_to_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }
}