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
        $flight->setArrivalTime(2300);
    }

    public function testValidArrivalTime() {
        $flight = new FlightModel();
        $validTime = "15:00";
        $flight->setArrivalTime($validTime);
        $this->assertEquals($validTime, $flight->getArrivalTime());
    }

    public function testValidDepartureTime() {
        $flight = new FlightModel();
        $validTime = "15:00";
        $flight->setDepartureTime($validTime);
        $this->assertEquals($validTime, $flight->getDepartureTime());
    }

    public function testInvalidArrival() {
        $flight = new FlightModel();
        $this->expectException(Exception::class);
        $flight->setArrivalTime(1588);
    }

    public function testEarlyDeparture() {
        $flight = new FlightModel();
        $this->expectException(Exception::class);
        $flight->setDepartureTime(0600);
    }

    public function testInvalidDeparture() {
        $flight = new FlightModel();
        $this->expectException(Exception::class);
        $flight->setDepartureTime(1588);
    }

    public function testInvalidArrivalAirport() {
        $flight = new FlightModel();
        $this->expectException(Exception::class);
        $flight->setArrivesAt("NotValidId");
    }

    public function testInvalidDepartureAirport() {
        $flight = new FlightModel();
        $this->expectException(Exception::class);
        $flight->setDepartsFrom("NotValidId");
    }

    public function testValidArrivalAirport() {
        $flight = new FlightModel();
        $validCode = 'XQU';
        $flight->setArrivesAt($validCode);
        $this->assertEquals($validCode, $flight->getArrivesAt());
    }

    public function testValidDeparturelAirport() {
        $flight = new FlightModel();
        $validCode = 'XQU';
        $flight->setDepartsFrom($validCode);
        $this->assertEquals($validCode, $flight->getDepartsFrom());
    }

    public function testExistingId() {
        $flight = new FlightModel();
        $this->expectException(Exception::class);
        $flight->setId('KYPRZMT');
    }

}
