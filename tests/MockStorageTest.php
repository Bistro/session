<?php

use \Bistro\Session\MockArray;

class MockStorageTest extends PHPUnit_Framework_TestCase
{
	public $session;

	public function setUp()
	{
		$data = array('user' => null);
		$this->session = new MockArray($data);
	}

	public function testStartOnConstruct()
	{
		$this->assertTrue($this->session->isStarted());
	}

	public function testDefaultData()
	{
		$this->assertNull($this->session->get('user'));
	}

	public function testHasKeyIsTrue()
	{
		$this->assertTrue($this->session->has('user'));
	}

	public function testHasMissingKeyIsFalse()
	{
		$this->assertFalse($this->session->has('missing'));
	}

	public function testGetDefault()
	{
		$value = $this->session->get('missing', false);
		$this->assertFalse($value);
	}

	public function testSetData()
	{
		$this->session->set('missing', true);
		$this->assertTrue($this->session->get('missing'));
	}

	public function testDelete()
	{
		$this->session->delete('user');
		$this->assertFalse($this->session->has('user'));
	}

	public function testGetOnce()
	{
		$this->session->set('flash_message', "Success!!");
		$value = $this->session->getOnce('flash_message');

		$this->assertSame("Success!!", $value);
		$this->assertFalse($this->session->has('flash_message'));
	}

	public function testRegenerate()
	{
		$id = $this->session->getId();
		$this->session->regenerate();

		$this->assertNotSame($id, $this->session->getId());
	}

	public function testDestroy()
	{
		$this->session->destroy();

		$this->assertFalse($this->session->has('user'));
		$this->assertFalse($this->session->isStarted());
	}

	public function testReadReplacesData()
	{
		$data = array('user' => "Dave");
		$this->session->read($data);

		$this->assertSame('Dave', $this->session->get('user'));
	}

	public function testClose()
	{
		$this->session->close();
		$this->assertFalse($this->session->isStarted());
	}

}
