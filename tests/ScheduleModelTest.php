<?php

use PHPUnit\Framework\TestCase;



class ScheduleModelTest extends TestCase
{

    private $CI;

    public function setUp()
    {
        $this->CI = &get_instance();
    }

    public function testValidSchedule() {
        $schedule = new ScheduleModel();
        $flight = new FlightModel();
        $flight->id = 'testFlight';
        $flight->departsFrom = 'YZP';
        $flight->arrivesAt = 'ZMT';
        $flight->departureTime = '13:00';
        $flight->arrivalTime = '14:00';
        $flight->plane = 'Kpc12ng1';
        $this->assertTrue($schedule->validSchedule($flight));
    }

    public function testInvalidScheduledBaseReturn() {
        $schedule = new ScheduleModel();
        $flight = new FlightModel();
        $flight->id = 'testFlight';
        $flight->departsFrom = 'YPR';
        $flight->arrivesAt = 'ZMT';
        $flight->departureTime = '13:00';
        $flight->arrivalTime = '14:00';
        $flight->plane = 'Kpc12ng1';
        $this->assertFalse($schedule->validSchedule($flight));
    }

    public function testValidScheduledBaseReturn() {
        $schedule = new ScheduleModel();
        $flight = new FlightModel();
        $flight->id = 'testFlight';
        $flight->departsFrom = 'ZMT';
        $flight->arrivesAt = 'YPR';
        $flight->departureTime = '13:00';
        $flight->arrivalTime = '14:00';
        $flight->plane = 'Kpc12ng1';
        $this->assertTrue($schedule->validSchedule($flight));
    }
}
