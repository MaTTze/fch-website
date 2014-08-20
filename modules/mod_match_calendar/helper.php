<?php
/**
 * Helper class for Hello World! module
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:modules/
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class modMatchCalendarHelper
{
    /**
     * Retrieves the hello message
     *
     * @param array $params An object containing the module parameters
     * @access public
     */    
    public static function getCalendar( $params, $today )
    {
        	
		$cal = '<div id="calendar">';
		$matches = /*array();//*/self::getMatches($params);
        $month = $today->format('m');
		$year = $today->format('Y');
		$cal = $cal.self::generateCalendar($month, $year, $matches, 'current');
		$month++;
		$cal = $cal.self::generateCalendar($month, $year, $matches, 'next');
		$cal = $cal.'</div>';
        return $cal;
    }
	
	private static function generateCalendar($month, $year, $matches, $subclass) {
	    $firstDay = new DateTime();
		$firstDay = $firstDay->setDate($year, $month, 1);
		$weekDay = (date_format($firstDay, 'w')+6)%7;
		$daysInMonth = date_format($firstDay, 't');
		$firstShown = (clone $firstDay);
		$firstShown->modify("-".$weekDay." days");
		$daysToShow = ceil(($daysInMonth+$weekDay)/7)*7;
	
		$cal = '<div class="calMain '.$subclass.' ';
		if (strcmp($subclass, 'next')===0) {
			$cal = $cal.' hidden';
		};
		$cal = $cal.'"><table><thead>';
		$cal = $cal.'<tr><th';
		if (strcmp($subclass, 'next')===0) {
			$cal = $cal.' class="current"';
		}
		$cal = $cal.'></th><th colspan="2">'.$firstDay->format('M').'</th><th colspan="3">'.$firstDay->format('Y').'</th><th';
		if (strcmp($subclass, 'current')===0) {
			$cal = $cal.' class="next"';
		}
		$cal = $cal.'></th></tr>';
		$cal = $cal.'<tr><th>Mo</th><th>Di</th><th>Mi</th><th>Do</th><th>Fr</th><th>Sa</th><th>So</th></tr></thead>';
		$cal = $cal.'<tbody>';
		
		$d = clone $firstShown;
		
		$j= array();
		foreach ($matches as $key => $value) {
			$j[$key] = 0;
			while ($d > $value[$j[$key]]['start']) { //skip events in the past
				$j[$key] = $j[$key]+1;
			}
		}
	    
	    for ($i=0;$i<$daysToShow;$i++){
	    	$w = $d->format('w');
			$match = FALSE;
			if ($w==1){//monday -> start new week/row
				$cal = $cal."<tr>";
			}
			$cal = $cal.'<td><div class="day-number';
			if ($d->format('m') <> $month) {$cal = $cal." out";} //this day does not belong to the displayed month
			foreach ($matches as $key => $value) {
				if (strcmp($d->format('dmY'), $value[$j[$key]]['start']->format('dmY'))===0) {
					if ($match === FALSE) {
						$cal = $cal.' match';
					}
					$match = TRUE;
					$cal = $cal.' '.$key;
				}
			}
			$cal = $cal.'">'.$d->format('d');
			if ($match){
				$cal = $cal.'<div class="popup">';
				foreach ($matches as $key => $value) {
					if (strcmp($d->format('dmY'), $value[$j[$key]]['start']->format('dmY'))===0) {
						$cal = $cal.'<div><span class="summary">'.strstr($value[$j[$key]]['summary'],'\,', TRUE).'</span><span class="time">'.$value[$j[$key]]['start']->format('H:i').'</span><span class="location">'.(str_replace('\,', '<br>', $value[$j[$key]]['location'])).'</span></div>';
					}
				}
				$cal = $cal.'</div>';
				
			}
			$cal = $cal."</div></td>";
			
			if ($w==0){//sunday -> end of week/row
				$cal = $cal."</tr>";
			}
			foreach ($matches as $key => $value) {
				while ($d > $value[$j[$key]]['start']) { //skip done events
					$j[$key] = $j[$key]+1;
				}
			}
			$d = $d->modify("+1 days");
	    }
		$cal = $cal.'</tbody><tfoot><tr><td colspan="7">Rot:&nbsp;Erste | Blau:&nbsp;Zweite | Grün:&nbsp;Sen&nbsp;A | Gelb:&nbsp;Sen&nbsp;C</td></tr></tfoot></table></div>';
		return $cal;
	}


	private static function getMatches($params)
	{
		$matches = array();
		$caching = $params->get("caching");
		for ($i=1; $i < 5; $i++) {
			$tag = 'match'.$i;
			$link = $params->get($tag);
			if ($link) {
				$string = self::get_ical($link, $caching);
				if ($string) {
					$matches[$tag] = self::parse_ical($string);
				}
			}
		}
		
		
		//$matches['match1'] = self::parse_ical("http://bfv.de/rest/icsexport/Spielplan?staffel=01L7JNAOQC000001VV0AG812VVHQG9J2-G&id=00ES8GNH9C00000AVV0AG08LVUPGND5I");
		//$matches['match2'] = self::parse_ical("http://bfv.de/rest/icsexport/Spielplan?staffel=01L7JPIC4O000001VV0AG812VVHQG9J2-G&id=00ES8GNH9C00000AVV0AG08LVUPGND5I");
		
		return $matches;
	}
	/**
	 * 
	 */
	private static function parse_ical($string){
		
		$string = htmlspecialchars($string);
		// Replace \r\n with \n
		$string = str_replace("\r\n", "\n", $string);
		// Unfold multi-line strings
		$string = str_replace("\n ", "", $string);
		$state = '';
		$count = 0;
		$events = array();
		
		foreach (explode("\n", $string) as $row) {
			switch ($state) {
				case '':
					if ($row === 'BEGIN:VCALENDAR') {
						$state = 'calendar';
					} else {
						//$firephp->log('Invalid ICAL data format');
						return array();
					}
					break;
				case 'calendar':
					switch ($row) {
						case 'BEGIN:VEVENT':
							$state = 'event';
							break;
						case 'END:VCALENDAR':
							break 3; //foreach
						default:
							break;
					}
					break;
				case 'event':
					$parts = explode(':', $row, 2);
					switch ($parts[0]) {
						case 'DTSTART':
							$start = new DateTime($parts[1]);
							$start = $start->modify('+2 hours');
							break;
						case 'SUMMARY':
							$summary = $parts[1];
							break;
						case 'LOCATION':
							$location = $parts[1];
							break;
						case 'END':
							if ($parts[1] === 'VEVENT') {
								if (stripos($summary, 'spielfrei') === FALSE) {
									$events[$count] = array('start' => $start, 'summary' => $summary, 'location' => $location);
									$count = $count+1;
								}
								$state = 'calendar';
							}
							break;
						case 'UID':
						case 'DTSTAMP':
						case 'DTEND':
						default:
							break;
					}
					break;
				default:
					break;
			}
		}

		usort($events, function($a, $b) { //sort event by start
			if ($a['start'] == $b['start']) {
				return 0;
			}
			return $a['start'] < $b['start'] ? -1 : 1;
		});
		return $events;
	}

	private function get_ical($url, $caching)
	{
		$filename = JPATH_ROOT.'/tmp/mod_match_calendar-'.md5($url).'.cache';
		if ($caching) {
			if (file_exists($filename) && (filemtime($filename) + 10800/* 3*60*60 sec */ >= time())){
				return file_get_contents($filename);
			}
		}
		$ctx = stream_context_create(array(
 			'http' => array(
    		'timeout' => 1
        	)
		));
		$string = file_get_contents($url,0,$ctx);
		//$firephp = FirePHP::getInstance(true);
 		//$firephp->log('starting to parse');
		if (!$string) {
			//$firephp->log('Can\'t open file' . $filename . ' for reading');
			return false;
		}
		if ($caching){
			file_put_contents($filename, $string);
		}
		return $string;
	}
}
?>
