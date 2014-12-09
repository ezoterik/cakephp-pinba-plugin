<?php
App::uses('Stopwatch', 'Pinba.Lib');

/**
 * Contains methods for creating timers.
 */
class PinbaTimer {

/**
 * Internal timers array
 *
 * @var StopwatchEvent[]
 */
	protected static $_timers = array();

/**
 * Start a timer.
 *
 * @param string $name The name of the timer to start
 * @param string $group Name of the group timer
 * @param string $operation Method name
 * @param string $server Server name
 * @param array $additionalTags Additional tags
 *
 * @return bool
 */
	public static function start($name, $group, $operation, $server = '', $additionalTags = array()) {
		if (empty($name)) {
			return false;
		}

		$stopwatch = Stopwatch::getInstance();

		$tags = $additionalTags;
		$tags['group'] = $group . '::' . $operation;
		if ($server != '') {
			$tags['server'] = $server;
		}

		if (isset(self::$_timers[$name])) {
			self::stop($name);
		}

		self::$_timers[$name] = $stopwatch->start($tags);

		return true;
	}

/**
 * Stop a timer.
 *
 * $name should be the same as the $name used in startTimer().
 *
 * @param string $name The name of the timer to end.
 *
 * @return bool true if timer was ended, false if timer was not started.
 */
	public static function stop($name) {
		if (empty($name)) {
			return false;
		}

		if (!isset(self::$_timers[$name])) {
			return false;
		}

		if (self::$_timers[$name] !== null) {
			self::$_timers[$name]->stop();
		}
		unset(self::$_timers[$name]);

		return true;
	}

/**
 * Stop all existing timers
 *
 * @return void
 */
	public static function stopAll() {
		foreach (self::$_timers as $e) {
			if ($e !== null) {
				$e->stop();
			}
		}

		self::$_timers = array();
	}
}
