<?php

/**
 * Dummy model class that represent our airline's flight schedule
 */
class ScheduleModel extends CSV_Model
{

    // Constructor
    public function __construct()
    {
        parent::__construct(APPPATH . DATA_FLIGHTS, 'id');
    }

    // Gets the departure airport
    // requires that the airport model has been loaded
    public function getDepartureAirport($key)
    {
        if (isset($this->data[$key]))
        {
            $record = $this->data[$key];
            return $this->airports->get($record['departsFrom']);
        }
        return null;
    }

    // Gets the arrival airport
    // requires that the airport model has been loaded
    public function getArrivalAirport($key)
    {
        if (isset($this->data[$key]))
        {
            $record = $this->data[$key];
            return $this->airports->get($record['arrivesAt']);
        }
        return null;
    }

    // Gets the plane
    // Requires that the fleet model has been loaded
    public function getPlane($key)
    {
        if (isset($this->data[$key]))
        {
            $record = $this->data[$key];
            return $this->fleet->get($record['plane']);
        }
        return null;
    }

    function validSchedule($flight) {
        $schedule = $this->all();
        array_push($schedule, $flight);

        return $this->validNetworkVisits($schedule) && $this->validPlaneBaseReturn($schedule);
    }

    /*
    * Validator for the flight schedule.
    * param $schedule - Array representing the airline schedule.
    * An schedule will be considered invalid and returns false if:
    *   - A airport in the airline network hasn't been visited twice.
    */
    public function validNetworkVisits($schedule) {
        $arrivalCount = array();
        $airportIds = array_keys($this->airports->airportsWeService());

        foreach($schedule as $flight) {
            if (array_key_exists($flight->arrivesAt, $arrivalCount)) {
                $arrivalCount[$flight->arrivesAt] += 1;
            } else {
                $arrivalCount[$flight->arrivesAt] = 1;
            }
        }

        $lowVisitAirports = array_filter($airportIds, function($id) use ($arrivalCount) {
            return !array_key_exists($id, $arrivalCount) || $arrivalCount[$id] < 2;
        });
        return empty($lowVisitAirports);
    }

    /*
    * Validator for the flight schedule.
    * param $schedule - Array representing the airline schedule.
    * An schedule will be considered invalid and returns false if:
    *   - A plane doesn't return to base at the end of the day.
    */
    public function validPlaneBaseReturn($schedule) {
        $baseCount = 0;
        $baseId = $this->airports->getBaseAirportId();

        foreach($schedule as $flight) {
            if ($flight->departsFrom == $baseId) {
                $baseCount--;
            }
            if ($flight->arrivesAt == $baseId) {
                $baseCount++;
            }
        }
        return $baseCount >= 0;
    }

    public function addFlight($flight) {
        if($this->validSchedule($flight))
            $this->scheduleModel->add($flight);
        else
            throw new Exception("Flight causes invalid schedule");
    }

    public function updateFlight($flight) {
        if($this->validSchedule($flight))
            $this->update($flight);
        else
            throw new Exception("Flight causes invalid schedule");
    }
}
