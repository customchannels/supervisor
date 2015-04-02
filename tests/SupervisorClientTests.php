<?php 

namespace CustomChannels\Supervisor\Tests; 

use CustomChannels\Supervisor\SupervisorClient; 

class SupervisorClientTests extends \PHPUnit_Framework_TestCase
{
	protected $Client; 

	public function __construct()
	{
		$this->Client = new SupervisorClient('127.0.0.1', 9001, 'username', 'password'); 

		/* dd($this->Client->reloadConfig()); */

		/* dd($this->Client->methodHelp('supervisor.getAllConfigInfo')); */ 

		/* dd($this->Client->listMethods()); */ 
	}

	public function testGetAllConfigInfo()
	{
		$config = $this->Client->getAllConfig(); 
		$this->assertInternalType('array', $config);
		$this->assertArrayHasKey('group',  $config[0]);
	}

	public function testReloadConfig()
	{
		$this->assertInternalType('array', $this->Client->reloadConfig()); 
	}

	public function testListMethods()
	{
		$this->assertInternalType('array', $this->Client->listMethods()); 
	}

	public function testGetAllProcessInfo()
	{
		$this->Client->getAllProcessInfo();
	}

	public function testReadLog()
	{
		$this->assertInternalType('string', $this->Client->readLog()); 
	}

	public function testGetIdentification()
	{
		$this->assertInternalType('string', $this->Client->getIdentification());
	}

	public function testGetPID()
	{
		$this->assertInternalType('int', $this->Client->getPID()); 
	}

	public function testGetState()
	{
		$this->assertArrayHasKey('statename', $this->Client->getState()); 
	}

	public function testCheckConnection()
	{
		$this->assertTrue($this->Client->checkConnection()); 
	}
}
