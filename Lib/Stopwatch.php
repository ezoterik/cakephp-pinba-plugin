<?php
App::uses('StopwatchEvent', 'Pinba.Lib');

class Stopwatch {

	protected $_enabled = false;

	protected $_initTags = array();

/**
 * Construct
 */
	public function __construct() {
		$this->_enabled =
			function_exists('pinba_timer_start') &&
			function_exists('pinba_timer_stop') &&
			function_exists('pinba_timer_add') &&
			function_exists('pinba_get_info');
		if ($this->_enabled) {
			$pinbaData = pinba_get_info();
			if (isset($pinbaData['hostname'])) {
				$this->_initTags['__hostname'] = $pinbaData['hostname'];
			}
			if (isset($pinbaData['server_name'])) {
				$this->_initTags['__server_name'] = $pinbaData['server_name'];
			}
		}
	}

/**
 * Creates and starts new timer.
 *
 * @param array $tags An array of tags and their values in the form of "tag" => "value". Cannot contain numeric indexes for obvious reasons.
 * @return StopwatchEvent Always returns new timer resource object.
 */
	public function start(array $tags) {
		if ($this->_enabled) {
			$tags = array_merge($this->_initTags, $tags);
			if (isset($tags['group']) && !isset($tags['category']) && strpos($tags['group'], '::') !== false) {
				$v = explode('::', $tags['group']);
				if (count($v) > 0) {
					$tags['category'] = $v[0];
				}
			}
		}

		return new StopwatchEvent($this->_enabled ? pinba_timer_start($tags) : null);
	}

/**
 * Creates new timer. This timer is already stopped and have specified time value.
 *
 * @param array $tags An array of tags and their values in the form of "tag" => "value". Cannot contain numeric indexes for obvious reasons.
 * @param int $time Timer value for new timer
 * @return void
 */
	public function add(array $tags, $time) {
		if (!$this->_enabled) {
			return;
		}
		$tags = array_merge($this->_initTags, $tags);
		pinba_timer_add($tags, $time);
	}

/**
 * Returns a reference to the Stopwatch singleton object instance.
 *
 * @return Stopwatch
 */
	public static function getInstance() {
		static $instance = null;

		if (!$instance) {
			$instance = new Stopwatch();
		}

		return $instance;
	}
}
