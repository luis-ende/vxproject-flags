<?php

namespace App\Services;

trait GeoCalculatorService
{
    private string $extract_number_pattern
        = "/(?:-(?:[1-9](?:\\d{0,2}(?:,\\d{3})+|\\d*))|(?:0|(?:[1-9](?:\\d{0,2}(?:,\\d{3})+|\\d*))))(?:.\\d+|)/";

    public function DMSStringtoDEC(string $dms): float
    {
        preg_match_all($this->extract_number_pattern, $dms, $matches);
        if (!empty($matches) && count($matches[0]) === 3) {
            return $this->DMStoDEC($matches[0][0], $matches[0][1], $matches[0][2]);
        }

        return 0;
    }

    /**
     * Converts DMS ( Degrees / minutes / seconds ) to decimal format longitude / latitude.
     */
    public function DMStoDEC($deg, $min, $sec)
    {
        $sign = $deg < 0 ? -1 : 1;
        $deg = abs($deg) * 1000000;
        $min = abs($min) * 1000000;
        $sec = abs($sec) * 1000000;
        //return $deg+((($min*60)+($sec))/3600);
        return round($deg + ($min/60) + ($sec/3600)) * ($sign/1000000);
    }

    public function DECtoDMS($dec): array
    {

        // Converts decimal longitude / latitude to DMS
        // ( Degrees / minutes / seconds )

        // This is the piece of code which may appear to
        // be inefficient, but to avoid issues with floating
        // point math we extract the integer part and the float
        // part by using a string function.

        $vars = explode(".",$dec);
        $deg = $vars[0];
        $tempma = "0.".$vars[1];

        $tempma = $tempma * 3600;
        $min = floor($tempma / 60);
        $sec = $tempma - ($min*60);

        return ["deg"=>$deg, "min"=>$min, "sec"=>$sec];
    }
}