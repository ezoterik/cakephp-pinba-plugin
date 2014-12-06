<?php
App::uses('Model', 'Model');

class TimerModel extends Model {

	/** @var Stopwatch|null */
	protected $_stopwatch = null;

	/** @var string */
	protected $_serverName = 'localhost';

/**
 * {@inheritDoc}
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->_stopwatch = new Stopwatch();
		$dsConfig = $this->getDataSource()->config;
		if (isset($dsConfig['host'])) {
			$this->_serverName = $dsConfig['host'];
			if (isset($dsConfig['port'])) {
				$this->_serverName .= ':' . $dsConfig['port'];
			}
		}
	}

/**
 * Timer start
 *
 * @param string $methodName Method name
 * @return StopwatchEvent
 */
	protected function _getStopwatchEvent($methodName) {
		$tags = array(
			'group' => 'orm::' . $methodName,
			'model' => $this->alias
		);

		if ($this->_serverName) {
			$tags['server'] = $this->_serverName;
		}

		return $this->_stopwatch->start($tags);
	}

/**
 * {@inheritDoc}
 */
	public function find($type = 'first', $query = array()) {
		$e = null;
		if ($this->_stopwatch) {
			$e = $this->_getStopwatchEvent('find');
		}

		$result = parent::find($type, $query);

		if ($e !== null) {
			$e->stop();
		}

		return $result;
	}

/**
 * {@inheritDoc}
 */
	public function save($data = null, $validate = true, $fieldList = array()) {
		$e = null;
		if ($this->_stopwatch) {
			$e = $this->_getStopwatchEvent('save');
		}

		$result = parent::save($data, $validate, $fieldList);

		if ($e !== null) {
			$e->stop();
		}

		return $result;
	}

/**
 * {@inheritDoc}
 */
	public function updateAll($fields, $conditions = true) {
		$e = null;
		if ($this->_stopwatch) {
			$e = $this->_getStopwatchEvent('updateAll');
		}

		$result = parent::updateAll($fields, $conditions);

		if ($e !== null) {
			$e->stop();
		}

		return $result;
	}

/**
 * {@inheritDoc}
 */
	public function delete($id = null, $cascade = true) {
		$e = null;
		if ($this->_stopwatch) {
			$e = $this->_getStopwatchEvent('delete');
		}

		$result = parent::delete($id, $cascade);

		if ($e !== null) {
			$e->stop();
		}

		return $result;
	}

/**
 * {@inheritDoc}
 */
	public function query($sql, $cachequeries = true) {
		$e = null;
		if ($this->_stopwatch) {
			$e = $this->_getStopwatchEvent('query');
		}

		$result = parent::query($sql, $cachequeries);

		if ($e !== null) {
			$e->stop();
		}

		return $result;
	}
}