<?php

use PHPUnit\Framework\TestCase;

class FleetModelTest extends TestCase
{

    private $CI;

    public function setUp()
    {
        $this->CI = &get_instance();
    }

    public function testValidFleet() {
        $fleet = new FleetModel();
        $plane = new PlaneModel();
        $plane->price = 1;
        $this->assertTrue($fleet->validFleet($plane));
    }
    
    public function testOverBudgetFleet() {
        $fleet = new FleetModel();
        $plane = new PlaneModel();
        $plane->price = 99999999999;
        $this->assertFalse($fleet->validFleet($plane));
    }

}
