<?php

/**
 * Dummy model class that represents the airports our airline services
 */
class Airports extends CI_Model
{
    private $airlineInfo = null;
    private function airlineInfo()
    {
        if ($this->airlineInfo === null)
            $this->airlineInfo = json_decode(file_get_contents(WACKY_SERVER_URI_BASE . '/airlines/kite'));
        return $this->airlineInfo;
    }

    // Constructor
    public function __construct()
    {
        parent::__construct(APPPATH . DATA_AIRPORTS, 'id');
    }

    /**
     * Returns the ID of our base airport
     */
    public function getBaseAirportId()
    {
        return $this->airlineInfo()->base;
    }

    /**
     * Gets all airports
     */
    public function all()
    {
        $ret = array();
        $allAirports = json_decode(file_get_contents(WACKY_SERVER_URI_BASE . '/airports'));

        foreach ($allAirports as $airport)
            $ret[$airport->id] = $airport;

        return $ret;
    }

    /**
     * Gets all airports serviced by Lightning Air
     */
    public function airportsWeService()
    {
        $codes = [
            $this->airlineInfo()->base,
            $this->airlineInfo()->dest1,
            $this->airlineInfo()->dest2,
            $this->airlineInfo()->dest3 ];

        return array_filter($this->all(), function($a) use ($codes) {
            return in_array($a->id, $codes);
        });
    }

    /**
     * Gets a single airport by id
     */
    public function get($id)
    {
        return json_decode(file_get_contents(WACKY_SERVER_URI_BASE . '/airports/' . $id));
    }

}
