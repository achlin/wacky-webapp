<?php
use PHPUnit\Framework\TestCase;

require_once(APPPATH.'models/PlaneModel.php');

class PlaneModelTest extends TestCase
{

    private $CI;

    public function setUp()
    {
        $this->CI = &get_instance();
    }
   /*   - The Id contains non-alphanumeric characters
    *   - The Id is an empty String
    *   - The Id does not start with 'k' or 'K'
    *   - The Id already exists in the fleet.*/

    public function testNonAlphaNumericId() {
        $plane = new PlaneModel();
        $this->expectException(Exception::class);
        $plane->setId("@#$%!");
    }

    public function testEmptyId() {
        $plane = new PlaneModel();
        $this->expectException(Exception::class);
        $plane->setId("");
    }

    public function testNonK() {
        $plane = new PlaneModel();
        $this->expectException(Exception::class);
        $plane->setId("Y123");
    }

    public function testExistingId() {
        $plane = new PlaneModel();
        $this->expectException(Exception::class);
        $plane->setId("Kcaravan1");
    }

    public function testValidId() {
        $plane = new PlaneModel();
        $validId = "KABC123";
        $plane->setId($validId);
        $this->assertEquals($validId, $plane->getId());
    }
}
