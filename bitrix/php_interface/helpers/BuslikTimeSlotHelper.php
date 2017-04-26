<?php

class BuslikTimeSlotHelper
{
	var $dbLink;

    public function __construct()
    {
        global $DB;
      
		$this->dbLink=$DB;
    }
	
	public function getAvailableSlotsToday($zoneId)
	{		
		
			$slotz=array();			
			if(!empty($zoneId))
			{
				$startOfDay = floor (time() / 86400) * 86400;
                $endOfDay = $startOfDay + 86400;
				
				echo $selectSql = "SELECT * FROM `a_time_slots` WHERE `start` > {$startOfDay} and {$endOfDay} >`end` and `zoneId`={$zoneId} and `reserved`=0";

				$result = $this->dbLink->query($selectSql);
				
				while ($row = $result->Fetch())
				{
					$slotz[]=$row;
				}
			}
			
        return $slotz;
	}
	
}
