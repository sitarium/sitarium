<?php

class AvailabilityTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testAvailability()
    {
        $this->visit('/')
             ->see('Sitarium');
    }
}
