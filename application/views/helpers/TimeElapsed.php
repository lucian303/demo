<?php
/**
 * Zend_View_Helper_TimeElapsed
 * A class to return the string representation of how long ago a timestamp happened in minutes, hours, or days
 *
 * @author Lucian Hontau code adopted from http://punbb.informer.com/forums/topic/20566/time-stamp-in-the-format-3-days-ago-or-2-hours-ago/
 * @param string Time representation
 */
class Zend_View_Helper_TimeElapsed extends Zend_View_Helper_Abstract
{

	public $view;

	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}

	public function timeElapsed($timestamp)
	{
		$difference = time() - $timestamp;

		if($difference < 60) {
			($difference) == 1 ? $word = 'second' : $word = 'seconds';
			return $difference . " $word ago";
		}

		$difference = round($difference / 60);
		if($difference < 60) {
			($difference) == 1 ? $word = 'minute' : $word = 'minutes';
			return $difference . " $word ago";
		}

		$difference = round($difference / 60);
		if($difference < 24) {
			($difference) == 1 ? $word = 'hour' : $word = 'hours';
			return $difference . " $word ago";
		}

		$difference = round($difference / 24);
		if($difference < 7) {
			($difference) == 1 ? $word = 'day' : $word = 'days';
			return $difference . " $word ago";
		}

		($difference) == 1 ? $word = 'week' : $word = 'weeks';
		$difference = round($difference / 7);
		return $difference . " $word ago";
	}

}

