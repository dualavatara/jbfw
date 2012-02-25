<?php

namespace Admin\Extension\Session;

class NativeSessionStorage implements SessionInterface {

    protected $options;

    public function __construct(array $options = array())
    {
        $defaults = session_get_cookie_params();
		$defaults['httponly'] = isset($defaults['httponly']) ? $defaults['httponly'] : false;

        $this->options = array_merge($defaults, $options);

        if (isset($this->options['name'])) {
            session_name($this->options['name']);
        }
    }

    public function start()
    {
        session_set_cookie_params(
            $this->options['lifetime'],
            $this->options['path'],
            $this->options['domain'],
            $this->options['secure'],
            $this->options['httponly']
        );

        if (
			!ini_get('session.use_cookies') &&
			isset($this->options['id']) &&
			$this->options['id'] &&
			$this->options['id'] != session_id()
		) {
            session_id($this->options['id']);
        }

        session_start();
    }

    public function read($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    public function remove($key)
    {
        $retval = null;

        if (isset($_SESSION[$key])) {
            $retval = $_SESSION[$key];
            unset($_SESSION[$key]);
        }

        return $retval;
    }

    public function write($key, $data)
    {
        $_SESSION[$key] = $data;
    }
}