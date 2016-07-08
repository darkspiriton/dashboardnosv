<?php

namespace Dashboard\Http\Controllers\Util;

use Carbon\Carbon;

class Oxigeno
{
	private $dates = array();
	private $months = ["","enero","febrero","marzo","mayo","abril","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"];
	private $month;

	public function __construct($year)
	{
		$dt = ($year)?Carbon::create($year,1,1,0,0,0):Carbon::now();
		$this->month = $dt->month;

        $this->dates[] = null;

        for ($i = 1; $i <= 12;$i++){
            $name = $this->months[$i];
            $date = Carbon::create($dt->year,$i,1,0,0,0);
            $temp = array();

            $temp["name"] = $name;
            $temp["month"] = $i;
            $temp["start"] = $date->toDateString();
            $temp["end"] = $date->copy()->lastOfMonth()->setTime(23,59,59)->toDateString();

            array_push($this->dates, $temp);
        }

        return $this;
	}

	/**
	 * Retorna detalle del mes consultado
	 *	@param int(2)	$i
	 *	@return Object
	 */
	public function getMonth($i)
	{
    	return (object)$this->dates[$i];
	}

	public function currentMonth()
	{
		return $this->month;
	}

	public function all(){
		return $this->dates;
	}

	/**
	 * Retorna todas la fechas de inicio y fin de cada mes
	 * del año actual o año asignado
	 *	@param int 		$year
	 *	@return Array
	 */
    static function generate($year = null)
    {
    	return new static($year);
    }

}
