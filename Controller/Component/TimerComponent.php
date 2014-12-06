<?php

/**
 * Class TimerComponent
 */
class TimerComponent extends Component {

/**
 * {@inheritDoc}
 */
	public function beforeRender(Controller $controller) {
		$controller->helpers = array_merge(array('Pinba.PinbaTimer'), $controller->helpers);
	}
}