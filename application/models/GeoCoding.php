<?php
class GeoCoding extends CI_Model {
    public function __construct(){ parent::__construct(); }

    public function countyValue(){
        if($this->Sess->getChain("city","registration/nmhh") != null){
            $city = $this->Sess->getChain("city","registration/nmhh");
            $county = $this->getCountyByCity($city,"county");
            return $county;
        }else{
            return "";
        }
    }
    public function getCountyByCity($city, $need) {
        $record = $this->db
            ->select('cities.name, counties.name as county, countries.name as country')
            ->from('cities')
            ->join('counties', 'cities.county_code = counties.code')
            ->join('countries', 'countries.code = counties.country')
            ->where('cities.name', $city)
            ->get()
            ->result_array()[0];
        return $record[$need];
    }
    public function getCountryLocalName($name){
        if($name == "Hungary"){ return "Magyarország"; };
        return $name;
    }
}
