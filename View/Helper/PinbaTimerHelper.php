<?php
App::uses('Helper', 'View');
App::uses('Stopwatch', 'Pinba.Lib');

/**
 * Class PinbaTimerHelper
 * Tracks time usage while rendering view.
 */
class PinbaTimerHelper extends Helper {

	/** @var Stopwatch|null */
	protected $_stopwatch = null;

	/** @var StopwatchEvent|null */
	protected $_stopwatchEvent = null;

/**
 * {@inheritDoc}
 */
	public function __construct(View $View, $settings = array()) {
		$this->_stopwatch = new Stopwatch();

		parent::__construct($View, $settings);
	}

/**
 * {@inheritDoc}
 */
	public function beforeRender($viewFile) {
		$this->_stopwatchEvent = $this->_stopwatch->start(array(
			'server' => 'localhost',
			'group' => 'view::render',
			'view_template' => basename($viewFile),
		));
	}

/**
 * {@inheritDoc}
 */
	public function afterRender($viewFile) {
		if ($this->_stopwatchEvent) {
			$this->_stopwatchEvent->stop();
		}
	}
}
