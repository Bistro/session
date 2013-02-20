<?php

namespace Bistro\Session;

/**
 * Storage engine for native php sessions.
 *
 * @package  Bistro
 */
class Native extends \Bistro\Hash implements \Bistro\Session\Session
{
	/**
	 * @var boolean  Is the session started yet?
	 */
	private $started = false;

	/**
	 * You can override php.ini session options in the constructor.
	 *
	 * Option                  | Default
	 * ------------------------|----------
	 * save_path               | ""
	 * name                    | "PHPSESSID"
	 * save_handler            | "files"
	 * auto_start              | "0"
	 * gc_probability          | "1"
	 * gc_divisor              | "100"
	 * gc_maxlifetime          | "1440"
	 * serialize_handler       | "php"
	 * cookie_lifetime 	       | "0"
	 * cookie_path             | "/"
	 * cookie_domain           | ""
	 * cookie_secure           | ""
	 * cookie_httponly         | ""
	 * use_cookies             | "1"
	 * use_only_cookies        | "1"
	 * referer_check           | ""
	 * entropy_file            | ""
	 * entropy_length          | "0"
	 * cache_limiter           | "nocache"
	 * cache_expire            | "180"
	 * use_trans_sid           | "0"
	 * hash_function           | "0"
	 * hash_bits_per_character | "4"
	 * url_rewriter.tags       | "a=href,area=href,frame=src,form=,fieldset="
	 *
	 * @see   http://php.net/session.configuration
	 * @param array $data      Initial Session data
	 * @param array $options   Session options
	 */
	public function __construct(array $data = null, array $options = array())
	{
		\register_shutdown_function(array($this, 'close'));

		$this->setOptions($options);
		$this->read($data);
	}

	/**
	 * Sets session options.
	 *
	 * @param array $options Options array
	 */
	protected function setOptions(array $options)
	{
		$whitelist = array('save_path', 'name', 'save_handler', 'auto_start',
			'gc_probability', 'gc_divisor', 'gc_maxlifetime', 'serialize_handler', 
			'cookie_lifetime', 'cookie_path', 'cookie_domain', 'cookie_secure',
			'cookie_httponly', 'use_cookies', 'use_only_cookies', 'referer_check',
			'entropy_file', 'entropy_length', 'cache_limiter', 'cache_expire',
			'use_trans_sid ', 'hash_function', 'hash_bits_per_character');

		foreach ($options as $key => $value)
		{
			if (\in_array($key, $whitelist))
			{
				\ini_set("session.{$key}", $value);
			}
		}

		if (\array_key_exists('url_rewriter.tags', $options))
		{
			\ini_set('url_rewriter.tags', $options['url_rewriter.tags']);
		}
	}

/** =========================
    \Bistro\Session\Session
    ========================= */
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
		return \session_id();
	}

	/**
	 * {@inheritDoc}
	 */
	public function setId($id)
	{
		\session_id($id);
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
		return \session_regenerate_id();
	}

	/**
	 * {@inheritDoc}
	 */
	public function restart()
	{
		$this->read($this->toArray());
		return true;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see http://www.php.net/manual/en/function.session-destroy.php#example-4597
	 */
	public function destroy()
	{
		$this->clear();

		if (\ini_get('session.use_cookies'))
		{
			$params = \session_get_cookie_params();
			\setcookie(
				\ini_get('session.name'), '', time() - 4200,
				$params['path'], $params['domain'],
				$params['secure'], $params['httponly']
			);
		}

		$this->started = false;
		return \session_destroy();
	}

	/**
	 * {@inheritDoc}
	 */
	public function read(array $data = null)
	{
		if ( ! $this->started)
		{
			\session_start();
			$this->started = true;
		}

		if ($data === null)
		{
			$data = $_SESSION;
		}

		$this->replace($data);
		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function write()
	{
		$_SESSION = $this->toArray();
		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function close()
	{
		$this->started = false;
		$this->write();

		\session_write_close();
		return $this;
	}

}
