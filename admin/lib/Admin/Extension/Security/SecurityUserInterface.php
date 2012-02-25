<?php

namespace Admin\Extension\Security;

interface SecurityUserInterface {
	
	/**
	 * Constructor.
	 * 
	 * @param \Admin\Application $app
	 */
	public function __construct(\Admin\Application $app);
	
	/**
	 * Validate user credentials.
	 * Return true if validation passed, false otherwise.
	 * 
	 * @abstract
	 * @param string $login
	 * @param string $password
	 * @return boolean
	 */
	public function authenticate($login, $password);
	
	/**
     * Checks if the user is authenticated or not.
     *
     * @return Boolean true if the user is authenticated, false otherwise
     */
    public function isAuthenticated();
	
	
	/**
	 * Logout user.
	 * 
	 * @abstract
	 * @return void
	 */
	public function logout();
	
	/**
	 * Return value of user model field.
	 * 
	 * @abstract
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name);

	/**
	 * Checks that user can get access to specified route
	 *
	 * @param string $name Route name
	 *
	 * @return bool
	 */
	public function checkRoute($name);
}