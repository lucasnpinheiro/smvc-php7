<?php

/**
 * Date Helper
 *
 * @author David Carr - dave@daveismyname.com
 * @version 1.0
 * @date May 18 2015
 * @date updated Sept 19, 2015
 */
namespace Helpers;

/**
 * collection of methods for working with dates.
 */
class Calendar {

	/**
	 * get the difference between 2 dates
	 *
	 * @param date $from
	 *        	start date
	 * @param date $to
	 *        	end date
	 * @param string $type
	 *        	the type of difference to return
	 * @return string or array, if type is set then a string is returned otherwise an array is returned
	 */
	public static function difference($from, $to, $type = null) {
		$d1 = new \DateTime($from);
		$d2 = new \DateTime($to);
		$diff = $d2->diff($d1);
		if ($type == null) {
			// return array
			return $diff;
		} else {
			return $diff->$type;
		}
	}

	/**
	 * Business Days
	 *
	 * Get number of working days between 2 dates
	 *
	 * Taken from http://mugurel.sumanariu.ro/php-2/php-how-to-calculate-number-of-work-days-between-2-dates/
	 *
	 * @param date $startDate
	 *        	date in the format of Y-m-d
	 * @param date $endDate
	 *        	date in the format of Y-m-d
	 * @param booleen $weekendDays
	 *        	returns the number of weekends
	 * @return integer returns the total number of days
	 */
	public static function businessDays($startDate, $endDate, $weekendDays = false) {
		$begin = strtotime($startDate);
		$end = strtotime($endDate);
		
		if ($begin > $end) {
			// startDate is in the future
			return 0;
		} else {
			$numDays = 0;
			$weekends = 0;
			
			while ($begin <= $end) {
				$numDays ++; // no of days in the given interval
				$whatDay = date('N', $begin);
				
				if ($whatDay > 5) { // 6 and 7 are weekend days
					$weekends ++;
				}
				$begin += 86400; // +1 day
			}
			;
			
			if ($weekendDays == true) {
				return $weekends;
			}
			
			$working_days = $numDays - $weekends;
			return $working_days;
		}
	}

	/**
	 * get an array of dates between 2 dates (not including weekends)
	 *
	 * @param date $startDate
	 *        	start date
	 * @param date $endDate
	 *        	end date
	 * @param integer $nonWork
	 *        	day of week(int) where weekend begins - 5 = fri -> sun, 6 = sat -> sun, 7 = sunday
	 * @return array list of dates between $startDate and $endDate
	 */
	public static function businessDates($startDate, $endDate, $nonWork = 6) {
		$begin = new \DateTime($startDate);
		$end = new \DateTime($endDate);
		$holiday = [ ];
		$interval = new \DateInterval('P1D');
		$dateRange = new \DatePeriod($begin, $interval, $end);
		foreach ($dateRange as $date) {
			if ($date->format("N") < $nonWork and ! in_array($date->format("Y-m-d"), $holiday)) {
				$dates[] = $date->format("Y-m-d");
			}
		}
		return $dates;
	}

	public function setDateFormat($dateFormat) {
		$this->_dateFormat = $dateFormat;
	}

	public function setTimeFormat($timeFormat) {
		$this->_timeFormat = $timeFormat;
	}

	public function convertDateToTimeStamp($dateString) {
		return strtotime($dateString);
	}

	public function convertTimeStampToDate($time, $dateFormat = false) {
		if (! $dateFormat)
			$dateFormat = $this->_dateFormat;
		return date($dateFormat, strtotime($time));
	}

	public static function now() {
		return date("Y-m-d H:i:s");
	}

	public static function getSqlDate() {
		return date("Y-m-d");
	}

	public static function getSqlDateTime() {
		return date("Y-m-d H:i:s");
	}

	/**
	 *
	 * @param type $dateFormat        	
	 * @return type
	 */
	public static function getThisDate($dateFormat = 'Y-m-d') {
		return date($dateFormat);
	}

	/**
	 *
	 * @param type $dateFormat
	 *        	default: F d, Y - h:i:s A
	 * @return string e.g.Aug 15, 1947 - 12:34:55
	 *         See also getThisDate()
	 */
	public static function getThisDateTime($dateFormat = 'F d, Y - h:i:s A') {
		return date($dateFormat, time());
	}

	public function getWeekWithDate($dateString) {
		$timeStamp = $this->convertDateToTimeStamp($dateString);
		$weekDay = date('w', $timeStamp);
		$startDateTimeStamp = $timeStamp - ($weekDay * 24 * 60 * 60);
		
		$thisDateTimeStamp = $startDateTimeStamp;
		for ($i = 0; $i < 7; $i ++) {
			$datesArray[] = date($this->_dateFormat, $thisDateTimeStamp);
			$thisDateTimeStamp += (1 * 24 * 60 * 60);
		}
		return $datesArray;
	}

	public static function getMonthName($monthNumber, $returnShortName = false) {
		switch ($monthNumber) {
			case '1' :
				$monthName = 'January';
				break;
			case '2' :
				$monthName = 'February';
				break;
			case '3' :
				$monthName = 'March';
				break;
			case '4' :
				$monthName = 'April';
				break;
			case '5' :
				$monthName = 'May';
				break;
			case '6' :
				$monthName = 'June';
				break;
			case '7' :
				$monthName = 'July';
				break;
			case '8' :
				$monthName = 'August';
				break;
			case '9' :
				$monthName = 'September';
				break;
			case '10' :
				$monthName = 'October';
				break;
			case '11' :
				$monthName = 'November';
				break;
			case '12' :
				$monthName = 'December';
				break;
		}
		return $returnShortName ? substr($monthName, 0, 3) : $monthName;
	}

	public function getDatesInMonth($month, $year, $returnType = 'normal', $dateFormat = null) {
		if ($dateFormat == null)
			$dateFormat = $this->_dateFormat;
		
		$timeStamp = strtotime('01-' . $month . '-' . $year . ' 00:00:01');
		
		$startDate = date($dateFormat, $timeStamp);
		$daysInThisMonth = date('t', $timeStamp);
		
		$datesArray = [ ];
		if ($returnType == 'weekwise') {
			$datesArray[0] = array ('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
			$weekCount = 1;
			for ($i = 1; $i <= $daysInThisMonth; $i ++) {
				$weekDay = date('w', $timeStamp);
				if ($weekDay == 0)
					$weekCount += 1;
				$datesArray[$weekCount][$weekDay] = date($dateFormat, $timeStamp);
				$timeStamp += (1 * 24 * 60 * 60);
			}
		} else {
			for ($i = 1; $i <= $daysInThisMonth; $i ++) {
				$datesArray[] = date($dateFormat, $timeStamp);
				$timeStamp += (1 * 24 * 60 * 60);
			}
		}
		return $datesArray;
	}

	public function getFirstDateInMonth($month = '', $year = '', $dateFormat = null) {
		if (empty($month)) {
			$month = date('m');
		}
		if (empty($year)) {
			$year = date('Y');
		}
		if (empty($dateFormat)) {
			$dateFormat = $this->_dateFormat;
		}
		
		$result = strtotime("{$year}-{$month}-01");
		return date($dateFormat, $result);
	}

	public function getLastDateInMonth($month = '', $year = '', $dateFormat = null) {
		if (empty($month)) {
			$month = date('m');
		}
		if (empty($year)) {
			$year = date('Y');
		}
		if (empty($dateFormat)) {
			$dateFormat = $this->_dateFormat;
		}
		
		$result = strtotime("{$year}-{$month}-01");
		$result = strtotime('-1 second', strtotime('+1 month', $result));
		return date($dateFormat, $result);
	}

	public function searchFirstDate($dateString, $dateFormat = 'd-M-Y') {
		$timestamp = strtotime($dateString);
		$month = date('m', $timestamp);
		$year = date('Y', $timestamp);
		$result = strtotime("{$year}-{$month}-01");
		return date($dateFormat, $result);
	}

	public function searchLastDate($dateString, $dateFormat = 'd-M-Y') {
		$timestamp = strtotime($dateString);
		$month = date('m', $timestamp);
		$year = date('Y', $timestamp);
		$result = strtotime("{$year}-{$month}-01");
		$result = strtotime('-1 second', strtotime('+1 month', $result));
		return date($dateFormat, $result);
	}

	public static function isValidDate($dateString) {
		$timestamp = strtotime($dateString);
		$month = date('m', $timestamp);
		$day = date('d', $timestamp);
		$year = date('Y', $timestamp);
		return checkdate($month, $day, $year);
	}

	public static function formatDate($date, $format = 'd-M-Y') {
		if ($date == '')
			return '';
		return date($format, strtotime($date));
	}

	public static function formatDateTimeFromTimestamp($timestamp, $format = 'd-M-Y H:i:s') {
		if ($timestamp == '')
			return '';
		return date($format, $timestamp);
	}

	public static function formatDateTime($date, $format = 'd-M-Y H:i:s') {
		if ($date == null)
			return '';
		return date($format, strtotime($date));
	}

	public static function formatSqlDate($date, $format = 'Y-m-d') {
		return date($format, strtotime($date));
	}

	public static function formatSqlDateTime($date, $format = 'Y-m-d H:i:s') {
		if ($date == '')
			return null;
		return date($format, strtotime($date));
	}

	public static function getTimezonesList() {
		$currentDefaultTimezone = date_default_timezone_get();
		$timezoneList = array ();
		$zones_array = array ();
		$timestamp = time();
		foreach (timezone_identifiers_list() as $key => $tmpTimezone) {
			date_default_timezone_set($tmpTimezone);
			$zones_array[$key]['zone'] = $tmpTimezone;
			$zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
		}
		foreach ($zones_array as $zones) {
			$timezoneList[$zones['zone']] = $zones['zone'] . " (" . $zones['diff_from_GMT'] . ")";
		}
		date_default_timezone_set($currentDefaultTimezone); // set back original
		return $timezoneList;
	}

	/*
	 * public static function getTimezonesList() {
	 * $timezoneIdentifiers = \DateTimeZone::listIdentifiers();
	 * $utcTime = new \DateTime('now', new \DateTimeZone('UTC'));
	 *
	 * $tempTimezones = [];
	 * foreach ($timezoneIdentifiers as $timezoneIdentifier) {
	 * $currentTimezone = new \DateTimeZone($timezoneIdentifier);
	 *
	 * $tempTimezones[] = array(
	 * 'offset' => (int) $currentTimezone->getOffset($utcTime),
	 * 'identifier' => $timezoneIdentifier
	 * );
	 * }
	 *
	 * // Sort the array by offset,identifier ascending
	 * usort($tempTimezones, function ($a, $b) {
	 * return ($a['offset'] == $b['offset']) ? strcmp($a['identifier'], $b['identifier']) : $a['offset'] - $b['offset'];
	 * });
	 *
	 * $timezoneList = [];
	 * foreach ($tempTimezones as $tz) {
	 * $sign = ($tz['offset'] > 0) ? '+' : '-';
	 * $offset = gmdate('H:i', abs($tz['offset']));
	 * $timezoneList[$tz['identifier']] = '(UTC ' . $sign . $offset . ') ' . $tz['identifier'];
	 * }
	 *
	 * return $timezoneList;
	 * }
	 */
	/* Modified from http://davidwalsh.name/php-calendar */
	public function renderHTMLCalendar($month, $year, $calendarDayEvents = null) {
		
		/* draw table */
		$calendar = '<table width="100%" border="1" cellpadding="0" cellspacing="0" class="HelpersCalendarTable">';
		
		/* table headings */
		$headings = array ('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
		$calendar .= '<tr class="HelpersCalendarRow"><th class="HelpersCalendarDayHeader" width="14%">' . implode('</th><th class="HelpersCalendarDayHeader" width="14%">', $headings) . '</th></tr>';
		
		/* days and weeks vars now ... */
		$mktime = mktime(0, 0, 0, $month, 1, $year);
		$running_day = date('w', $mktime);
		$days_in_month = date('t', $mktime);
		$short_month = date('m', $mktime);
		$days_in_this_week = 1;
		$day_counter = 0;
		$dates_array = [ ];
		
		/* row for week one */
		$calendar .= '<tr class="HelpersCalendarRow">';
		
		/* print "blank" days until the first of the current week */
		for ($x = 0; $x < $running_day; $x ++) :
			$calendar .= '<td class=""> </td>';
			$days_in_this_week ++;
		endfor
		;
		
		/* keep going with days.... */
		for ($list_day = 1; $list_day <= $days_in_month; $list_day ++) :
			$calendar .= '<td class="HelpersCalendarDay">';
			/* add in the day number */
			$calendar .= '<div class="HelpersCalendarDate"><a href="#">' . $list_day . '</a></div>';
			
			$calendar .= '<div class="HelpersCalendarDateContent">';
			$event_date = $year . '-' . $short_month . '-' . $list_day;
			if ($calendarDayEvents[$event_date] != '') {
				$calendar .= '<div style="background-color:' . ($calendarDayEvents[$event_date]['backgroundColor']) . '">';
				foreach ($calendarDayEvents[$event_date] as $eventEntry)
					$calendar .= '' . $eventEntry['entry'] . '';
				$calendar .= '</div>';
			}
			
			$calendar .= '</div>';
			
			$calendar .= str_repeat('<p> </p>', 2);
			
			$calendar .= '</td>';
			if ($running_day == 6) :
				$calendar .= '</tr>';
				if (($day_counter + 1) != $days_in_month) :
					$calendar .= '<tr class="HelpersCalendarRow">';
				
				
				


                endif;
				$running_day = - 1;
				$days_in_this_week = 0;
			
			
			


            endif;
			$days_in_this_week ++;
			$running_day ++;
			$day_counter ++;
		endfor
		;
		
		/* finish the rest of the days in the week */
		if ($days_in_this_week < 8) :
			for ($x = 1; $x <= (8 - $days_in_this_week); $x ++) :
				$calendar .= '<td class="calendar-day-np"> </td>';
			endfor
			;
		
		
		


        endif;
		
		/* final row */
		$calendar .= '</tr>';
		
		/* end the table */
		$calendar .= '</table>';
		
		/* all done, return result */
		return $calendar;
	}

	public static function secondsToTime($inputSeconds, $returnAsString = true) {
		$secondsInAMinute = 60;
		$secondsInAnHour = 60 * $secondsInAMinute;
		$secondsInADay = 24 * $secondsInAnHour;
		
		// extract days
		$days = floor($inputSeconds / $secondsInADay);
		
		// extract hours
		$hourSeconds = $inputSeconds % $secondsInADay;
		$hours = floor($hourSeconds / $secondsInAnHour);
		
		// extract minutes
		$minuteSeconds = $hourSeconds % $secondsInAnHour;
		$minutes = floor($minuteSeconds / $secondsInAMinute);
		
		// extract the remaining seconds
		$remainingSeconds = $minuteSeconds % $secondsInAMinute;
		$seconds = ceil($remainingSeconds);
		
		// return the final array
		$obj = array ('d' => (int) $days,'h' => (int) $hours,'m' => (int) $minutes,'s' => (int) $seconds);
		
		if ($returnAsString) {
			return ($days > 0 ? $days . 'd' : '') . ' ' . $hours . 'h ' . $minutes . 'm';
		} else {
			return $obj;
		}
	}
}
