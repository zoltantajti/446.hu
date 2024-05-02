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
}