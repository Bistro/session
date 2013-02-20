<?php

namespace Bistro\Session;

/**
 * A Mock Session storage engine. Use this for UnitTesting as accessing
 * $_SESSION will throw errors.
 *
 * @package  Bistro
 */
class MockArray extends \Bistro\Hash implements \Bistro\Session\Session
{
	/**
	 * @var boolean  Is the session started yet?
	 */
	private $started = false;

	/**
	 * @var string  The session id
	 */
	private $id;

	/**
	 * @param array $data  Initial session data
	 */
	public function __construct(array $data = null)
	{
		$this->regenerate();
		$this->read($data);
	}

/** ======================
    \Bistro\Session\Session
    ====================== */
	/**
	 * {@inheritDoc}
	 */
	public function isStarted()
	{
		return $this->started;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getOnce($key, $default = null)
	{
		$value = $this->get($key, $default);
		$this->delete($key);
		return $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function regenerate()
	{
		$this->setId(\uniqid());
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function restart()
	{
		$this->read($this->data->toArray());
	}

	/**
	 * {@inheritDoc}
	 */
	public function destroy()
	{
		$this->clear();
		$this->started = false;
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function read(array $data = null)
	{
		if ($data === null)
		{
			$data = array();
		}

		$this->started = true;

		$this->replace($data);
		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function write()
	{
		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function close()
	{
		$this->started = false;
		return $this;
	}

}
