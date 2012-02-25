<?php

namespace Admin;

class EventDispatcher {
	protected $listeners = array();

	/**
	 * Connects a listener to a given event name.
	 * You can connect two equal listeners to event and both will be executed when event will be fired
	 *
	 * @param string $name		An event name
	 * @param mixed  $listener  A PHP callable
	 */
	public function connect($name, $listener) {
		if (!isset($this->listeners[$name])) {
			$this->listeners[$name] = array();
		}

		$this->listeners[$name][] = $listener;
	}

	/**
	 * Disconnects a listener for a given event name.
	 * If you connect two equal listeners than both will be disconnected.
	 *
	 * @param string $name		An event name
	 * @param mixed	 $listener  A PHP callable
	 */
	public function disconnect($name, $listener) {
		if (!isset($this->listeners[$name])) {
			return;
		}

		foreach ($this->listeners[$name] as $i => $callable) {
			if ($listener === $callable) {
				unset($this->listeners[$name][$i]);
			}
		}
	}

	/**
	 * Returns all listeners associated with a given event name.
	 *
	 * @param  string $name The event name
	 *
	 * @return array  An array of listeners
	 */
	public function getListeners($name) {
		if (!isset($this->listeners[$name])) {
			return array();
		}

		return $this->listeners[$name];
	}

	/**
	 * Notifies all listeners of a given event.
	 *
	 * @param Event $event A Event instance
	 *
	 * @return array Results of listeners execution
	 */
	public function fire(Event $event) {
		$results = array();
		foreach ($this->getListeners($event->getName()) as $listener) {
			$results[] = call_user_func($listener, $event);
		}
		
		return $results;
	}
}
