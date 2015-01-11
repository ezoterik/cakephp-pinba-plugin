<?php
App::uses('Stopwatch', 'Pinba.Lib');
App::uses('MemcachedMultiEngine', 'Lib/Cache/Engine');

class MemcachedMultiTimerEngine extends MemcachedMultiEngine {

	/** @var Stopwatch|null */
	protected $_stopwatch = null;

	/** @var array */
	protected $_stopwatchAdditionalTags = array();

	/** @var string */
	protected $_serverName;

/**
 * {@inheritDoc}
 */
	public function init($settings = array()) {
		$this->setStopwatch(Stopwatch::getInstance());

		$result = parent::init($settings);

		if (isset($this->settings['servers'])) {
			$this->_serverName = reset($this->settings['servers']);
		}

		return $result;
	}

/**
 * Stopwatch setter
 *
 * @param Stopwatch $stopwatch Stopwatch object
 * @return void
 */
	public function setStopwatch(Stopwatch $stopwatch) {
		$this->_stopwatch = $stopwatch;
	}

/**
 * StopwatchTags setter
 *
 * @param array $tags Tags
 * @return void
 */
	public function setStopwatchTags(array $tags) {
		$this->_stopwatchAdditionalTags = $tags;
	}

/**
 * Timer start
 *
 * @param string $methodName Method name
 * @return StopwatchEvent
 */
	protected function _getStopwatchEvent($methodName) {
		$tags = $this->_stopwatchAdditionalTags;
		$tags['group'] = 'memcached::' . $methodName;
		if ($this->_serverName) {
			$tags['server'] = $this->_serverName;
		}

		return $this->_stopwatch->start($tags);
	}

/**
 * {@inheritDoc}
 */
	public function write($key, $value, $duration) {
		$e = null;
		if ($this->_stopwatch) {
			$e = $this->_getStopwatchEvent('write');
		}

		$result = parent::write($key, $value, $duration);

		if ($e !== null) {
			$e->stop();
		}

		return $result;
	}

/**
 * {@inheritDoc}
 */
	public function read($key) {
		$e = null;
		if ($this->_stopwatch) {
			$e = $this->_getStopwatchEvent('read');
		}

		$result = parent::read($key);

		if ($e !== null) {
			$e->stop();
		}

		return $result;
	}

/**
 * {@inheritDoc}
 */
	public function readMulti(array $keys) {
		$e = null;
		if ($this->_stopwatch) {
			$e = $this->_getStopwatchEvent('readMulti');
		}

		$result = parent::readMulti($keys);

		if ($e !== null) {
			$e->stop();
		}

		return $result;
	}

/**
 * {@inheritDoc}
 */
	public function increment($key, $offset = 1) {
		$e = null;
		if ($this->_stopwatch) {
			$e = $this->_getStopwatchEvent('increment');
		}

		$result = parent::increment($key, $offset);

		if ($e !== null) {
			$e->stop();
		}

		return $result;
	}

/**
 * {@inheritDoc}
 */
	public function decrement($key, $offset = 1) {
		$e = null;
		if ($this->_stopwatch) {
			$e = $this->_getStopwatchEvent('decrement');
		}

		$result = parent::decrement($key, $offset);

		if ($e !== null) {
			$e->stop();
		}

		return $result;
	}

/**
 * {@inheritDoc}
 */
	public function delete($key) {
		$e = null;
		if ($this->_stopwatch) {
			$e = $this->_getStopwatchEvent('delete');
		}

		$result = parent::delete($key);

		if ($e !== null) {
			$e->stop();
		}

		return $result;
	}

/**
 * {@inheritDoc}
 */
	public function clear($check) {
		$e = null;
		if ($this->_stopwatch) {
			$e = $this->_getStopwatchEvent('clear');
		}

		$result = parent::clear($check);

		if ($e !== null) {
			$e->stop();
		}

		return $result;
	}

/**
 * {@inheritDoc}
 */
	public function groups() {
		$e = null;
		if ($this->_stopwatch) {
			$e = $this->_getStopwatchEvent('groups');
		}

		$result = parent::groups();

		if ($e !== null) {
			$e->stop();
		}

		return $result;
	}

/**
 * {@inheritDoc}
 */
	public function clearGroup($group) {
		$e = null;
		if ($this->_stopwatch) {
			$e = $this->_getStopwatchEvent('clearGroup');
		}

		$result = parent::clearGroup($group);

		if ($e !== null) {
			$e->stop();
		}

		return $result;
	}
}
