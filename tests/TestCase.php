<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{


    protected function authenticateAs(User $user)
    {
        $this->actingAs($user, 'api');
        return $this;
    }
}
