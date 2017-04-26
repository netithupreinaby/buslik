<?php

namespace Yenisite\CoreParser;

/**
 * For use on options.php page of other modules
 */
class Options
{
	/**
	 * @var array notes for options
	 */
	protected static $arNotes = array();

	/**
	 * Add new note for some option
	 * @param string $note - text of note
	 * @return int - key for added note
	 */
	public static function addNote($note)
	{
		static $noteKey = 1;
		if (empty($note)) return;

		self::$arNotes[$noteKey] = $note;
		return $noteKey++;
	}

	/**
	 * Get full list of notes to show at the end of options list
	 * @return array
	 */
	public static function getNotes()
	{
		return self::$arNotes;
	}

	/**
	 * Saves common connection options into COption
	 * @param string $moduleId
	 */
	public static function saveConnection($moduleId)
	{
		$interval_min = intval($_REQUEST['interval_min']);
		$interval_max = intval($_REQUEST['interval_max']);
		$proxy_retries = intval($_REQUEST['proxy_retries']);
		$proxy_timeout = intval($_REQUEST['proxy_timeout']);

		$proxy_ip = \COption::GetOptionString($moduleId, 'proxy_ip', '');
		if ($proxy_ip != $_REQUEST['proxy_ip']) {
			unset($_SESSION[$moduleId]);
		}
		\COption::SetOptionString($moduleId, 'proxy_ip', $_REQUEST['proxy_ip']);

		if (!empty($interval_min) && !empty($interval_max))
		{
			if ($interval_min <= $interval_max
			&&  $interval_min >= 10
			&&  $interval_max >= 15)
			{
				\COption::SetOptionString($moduleId, 'interval_min', $interval_min);
				\COption::SetOptionString($moduleId, 'interval_max', $interval_max);
			}
		}
		if ($proxy_retries < 5 || $proxy_retries > 30) $proxy_retries = 0;
		if ($proxy_timeout < 5 || $proxy_timeout > 15) $proxy_timeout = 0;

		if (!empty($proxy_retries)) \COption::SetOptionInt($moduleId, 'proxy_retries', $proxy_retries);
		if (!empty($proxy_timeout)) \COption::SetOptionInt($moduleId, 'proxy_timeout', $proxy_timeout);
	}

	/**
	 * Saves common search options into COption
	 * @param string $moduleId
	 */
	public static function saveSearch($moduleId)
	{
		\COption::SetOptionString($moduleId, 'brands',          $_REQUEST['brands']);
		\COption::SetOptionString($moduleId, 'exception_words', $_REQUEST['exception_words']);
	}
}
