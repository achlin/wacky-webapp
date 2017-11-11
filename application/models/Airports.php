<?php

/**
 * Dummy model class that represents the airports our airline services
 */
class Airports extends CI_Model
{
    private $baseId;
    private $codesWeService;

    // Constructor
    public function __construct()
    {
        parent::__construct(APPPATH . DATA_AIRPORTS, 'id');
        $this->baseId = 'YPR';
        $this->codesWeService = ['YPR', 'ZMT', 'YZP', 'YXT'];
    }

    /**
     * Returns the ID of our base airport
     */
    public function getBaseAirportId()
    {
        return $this->baseId;
    }

    /**
     * Gets all airports
     */
    public function all()
    {
        $ret = array();
        $allAirports = json_decode(file_get_contents('https://wacky.jlparry.com/info/airports'));

        foreach ($allAirports as $airport)
            $ret[$airport->id] = $airport;

        return $ret;
    }

    /**
     * Gets all airports serviced by Lightning Air
     */
    public function airportsWeService()
    {
        return array_filter($this->all(), function($airport) { return in_array($airport->id, $this->codesWeService); });
    }

    /**
     * Gets a single airport by id
     */
    public function get($id)
    {
        return json_decode(file_get_contents('https://wacky.jlparry.com/info/airports/' . $id));
    }

}
