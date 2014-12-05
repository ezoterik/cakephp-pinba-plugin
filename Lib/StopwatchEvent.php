<?php

class StopwatchEvent {

	protected $_pinbaTimer;

/**
 * Construct
 *
 * @param resource|null $pinbaTimer Timer resource
 */
	public function __construct($pinbaTimer = null) {
		$this->_pinbaTimer = $pinbaTimer;
	}

/**
 * Stops the timer.
 *
 * @return void
 */
	public function stop() {
		if ($this->_pinbaTimer) {
			pinba_timer_stop($this->_pinbaTimer);
		}
	}
}