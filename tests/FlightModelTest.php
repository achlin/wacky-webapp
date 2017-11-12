<?php

use PHPUnit\Framework\TestCase;

require_once(APPPATH.'models/FlightModel.php');

class FlightModelTest extends TestCase
{

    private $CI;

    public function setUp()
    {
        $this->CI = &get_instance();
    }

    public function testLateArrival() {
        $flight = new FlightModel();
        $this->expectException(Exception::class);
        $flight->arrivalTime = 2300;
    }

    public function testValidArrivalTime() {
        $flight = new FlightModel();
        $validTime = "15:00";
        $flight->arrivalTime = $validTime;
        $this->assertEquals($validTime, $flight->arrivalTime);
    }

    public function testValidDepartureTime() {
        $flight = new FlightModel();
        $validTime = "15:00";
        $flight->departureTime = $validTime;
        $this->assertEquals($validTime, $flight->departureTime);
    }

    public function testInvalidArrival() {
        $flight = new FlightModel();
        $this->expectException(Exception::class);
        $flight->arrivalTime = 1588;
    }

    public function testEarlyDeparture() {
        $flight = new FlightModel();
        $this->expectException(Exception::class);
        $flight->departureTime = 0600;
    }

    public function testInvalidDeparture() {
        $flight = new FlightModel();
        $this->expectException(Exception::class);
        $flight->departureTime = 1588;
    }

    public function testInvalidArrivalAirport() {
        $flight = new FlightModel();
        $this->expectException(Exception::class);
        $flight->arrivesAt = "NotValidId";
    }

    public function testInvalidDepartureAirport() {
        $flight = new FlightModel();
        $this->expectException(Exception::class);
        $flight->departsFrom ="NotValidId";
    }

    public function testValidArrivalAirport() {
        $flight = new FlightModel();
        $validCode = 'XQU';
        $flight->arrivesAt = $validCode;
        $this->assertEquals($validCode, $flight->arrivesAt);
    }

    public function testValidDepartureAirport() {
        $flight = new FlightModel();
        $validCode = 'XQU';
        $flight->departsFrom = $validCode;
        $this->assertEquals($validCode, $flight->departsFrom);
    }

    public function testExistingId() {
        $flight = new FlightModel();
        $this->expectException(Exception::class);
        $flight->id = 'KYPRZMT';
    }

    public function testValidPlane() {
        $flight = new FlightModel();
        $validPlane = 'Kcaravan1';
        $flight->plane = $validPlane;
        $this->assertEquals($validPlane, $flight->plane);
    }

    public function testInvalidPlane() {
        $flight = new FlightModel();
        $this->expectException(Exception::class);
        $flight->plane = 'BLIMP';
    }

}
