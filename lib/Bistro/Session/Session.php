<?php

namespace Bistro\Session;

/**
 * Interface for all session storage engines.
 *
 * @package  Bistro
 */
interface Session
{
	/**
	 * @return boolean Has the session been started yet?
	 */
	public function isStarted();

	/**
	 * @return string  The session id
	 */
	public function getId();

	/**
	 * @param  string  $id       The session id
	 * @return \Bistro\Session   $this
	 */
	public function setId($id);

	/**
	 * Checks to see if a session property exists.
	 *
	 * @param  string $key     The session key
	 * @return boolean
	 */
	public function has($key);

	/**
	 * Gets a session variable by key.
	 *
	 * @param  string $key     The session key
	 * @param  mixed  $default The default value if one isn't found
	 * @return mixed           The found value
	 */
	public function get($key, $default = null);

	/**
	 * Gets a key from the session and removes it immediately.
	 * Useful in saving messages.
	 *
	 * @param  string $key     The key to get
	 * @param  mixed $default  The default value
	 * @return mixed           The found value
	 */
	public function getOnce($key, $default = null);

	/**
	 * Gets all of the session data
	 *
	 * @return array   The session data
	 */
	public function toArray();

	/**
	 * Sets a session variable.
	 *
	 * @param  string $key       The session key
	 * @param  mixed  $value     The value to set
	 * @return \Bistro\Session   $this
	 */
	public function set($key, $value);

	/**
	 * Removes a session key.
	 *
	 * @param  string $key       The session key to delete
	 * @return \Bistro\Session   $this
	 */
	public function delete($key);

	/**
	 * Clears out the session data.
	 *
	 * @return \Bistro\Session  $this
	 */
	public function clear();

	/**
	 * Regererates the session id.
	 *
	 * @return boolean
	 */
	public function regenerate();

	/**
	 * Restarts the current session.
	 *
	 * @return boolean   Success
	 */
	public function restart();

	/**
	 * Destroys the current session.
	 *
	 * @return boolean    Success
	 */
	public function destroy();

	/**
	 * Reads data from the storage engine.
	 *
	 * @param  array $data      Initial data
	 * @return \Bistro\Session  $this
	 */
	public function read(array $data = null);

	/**
	 * Writes the session data.
	 *
	 * @return \Bistro\Session  $this
	 */
	public function write();

	/**
	 * Closes the session.
	 *
	 * @return \Bistro\Session  $this
	 */
	public function close();

}
