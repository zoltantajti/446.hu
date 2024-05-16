<?php
class Misc extends CI_Model {
    public function __construct(){ parent::__construct(); }

    public function parseImage($image)
    {
        if(str_starts_with($image, "http://") || str_starts_with($image, "https://")){
            return $image;
        }elseif(str_starts_with($image, "./")){
            return $image;
        }else{
            return "./assets/images/" . $image;
        }
    }

    public function nominatim($in){
        $accentedChars = array('á', 'é', 'í', 'ó', 'ö', 'ő', 'ú', 'ü', 'ű', 'Á', 'É', 'Í', 'Ó', 'Ö', 'Ő', 'Ú', 'Ü', 'Ű');
        $normalChars = array('a', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'u', 'A', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'U');
        return str_replace($accentedChars, $normalChars, $in);
    }
    public function getCountryName($in){
        if($in == "Magyarország"){ return "Hungary"; }
        else{ return $in; };
    }
}