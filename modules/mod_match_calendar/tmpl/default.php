<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php 
	JHtml::_('jquery.framework');
	JHtml::script('mod_match_calendar/js/mod_match_calendar.js', FALSE, TRUE);
	JHtml::stylesheet('mod_match_calendar/css/mod_match_calendar.css', FALSE, TRUE);
	$document = JFactory::getDocument();
	$document->addScript(JUri::base().'media/mod_match_calendar/js/mod_match_calendar.js');
	$document->addStyleSheet(JUri::base().'media/mod_match_calendar/css/mod_match_calendar.css');
	echo $cal; 
?> 	