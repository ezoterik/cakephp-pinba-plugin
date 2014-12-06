<?php

/**
 * Class TimedBehavior
 */
class TimedBehavior extends ModelBehavior {

/**
 * Behavior settings
 *
 * @var array
 */
	public $settings = array();

/**
 * Default setting values
 *
 * @var array
 */
	protected $_defaults = array();

	/** @var Stopwatch|null */
	protected $_stopwatch = null;

	/** @var StopwatchEvent|null */
	protected $_stopwatchEventFind = null;

	/** @var StopwatchEvent|null */
	protected $_stopwatchEventSave = null;

	/** @var string */
	protected $_serverName = 'localhost';

/**
 * {@inheritDoc}
 */
	public function setup(Model $Model, $settings = null) {
		if (is_array($settings)) {
			$this->settings[$Model->alias] = array_merge($this->_defaults, $settings);
		} else {
			$this->settings[$Model->alias] = $this->_defaults;
		}

		$this->_stopwatch = new Stopwatch();
		$dsConfig = $Model->getDataSource()->config;
		if (isset($dsConfig['host'])) {
			$this->_serverName = $dsConfig['host'];
			if (isset($dsConfig['port'])) {
				$this->_serverName .= ':' . $dsConfig['port'];
			}
		}
	}

/**
 * {@inheritDoc}
 */
	public function beforeFind(Model $Model, $queryData) {
		$this->_stopwatchEventFind = $this->_stopwatch->start(array(
			'server' => $this->_serverName,
			'group' => 'orm::find',
			//'model' => $Model->alias,
		));
		return true;
	}

/**
 * {@inheritDoc}
 */
	public function afterFind(Model $Model, $results, $primary = false) {
		if ($this->_stopwatchEventFind) {
			$this->_stopwatchEventFind->stop();
		}
		return true;
	}

/**
 * {@inheritDoc}
 */
	public function beforeSave(Model $Model, $options = array()) {
		$this->_stopwatchEventSave = $this->_stopwatch->start(array(
			'server' => $this->_serverName,
			'group' => 'orm::save',
			//'model' => $Model->alias,
		));
		return true;
	}

/**
 * {@inheritDoc}
 */
	public function afterSave(Model $Model, $created, $options = array()) {
		if ($this->_stopwatchEventSave) {
			$this->_stopwatchEventSave->stop();
		}
		return true;
	}
}
