<?php


namespace App\Traits;


trait Util
{
    public function renombrarEncargado(&$encargado)
    {
        if(strpos($encargado,"BELMONT")!==false)
        {
            $encargado="CARMEN BELMONT";
        }
        else if(strpos($encargado,"CESAR")!==false)
        {
            $encargado="CESAR HUERTA";
        }
        else if(strpos($encargado,"LIMON")!==false)
        {
            $encargado="MARCO ANTONIO PINEDA LIMON";
        }
        else if(strpos($encargado,"TREVINO")!==false)
        {
            $encargado="TREVIÑO";
        }
    }
}
