<?php
/**
 * Match Calendar Module Entry Point
 * 
 *  */
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// Include the syndicate functions only once
require_once( dirname(__FILE__).'/helper.php' );
$today = $firstDay = new DateTime();
$cal = modMatchCalendarHelper::getCalendar( $params, $today);
require( JModuleHelper::getLayoutPath( 'mod_match_calendar' ) );
?>