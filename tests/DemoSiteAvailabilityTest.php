<?php

class DemoSiteAvailabilityTest extends TestCase
{
    protected $baseUrl = 'http://demo.localhost';
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testAvailability()
    {
        $this->visit('/') 
             ->see('Demo');
    }
}
