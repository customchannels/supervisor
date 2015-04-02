<?php

/**
 * 
 *
 * @author Steve Barbera
 # @date 2014-10-07
 * @version 0.1
 * @copyright Custom Channels, 07 October, 2014
 * @package CustomChannels\Supervisor			
 **/

namespace CustomChannels\Supervisor;

use Zend\XmlRpc\Client as RpcClient; 
use Zend\Http\Client as HttpClient; 

class SupervisorClient{

	protected $Client; 

	/**
	 * set the supervisor host and port
	 *
	 * @param $ip_address - string - supervisor ip address
	 * @param $port - integer - supervisor port
	 * @param $username - string - if one is set
	 * #param $password - string - if there is a passsword
	 * @return void
	 # @date 2014-10-07
	 **/
	public function __construct($ip_address, $port,  $username = null, $password = null)
	{
		$this->Client = new RpcClient('http://'.$ip_address.':'.$port.'/RPC2/'); 

		if($username != null && $password != null) {
			$this->Client->getHttpClient()->setAuth($username, $password, HttpClient::AUTH_BASIC); 
		}
	}

	/**
	 * Starts a supervisor process
	 *
	 * @return boolean
	 # @date 2014-10-10
	 **/
	public function startProcess($process_name, $wait = false)
	{
		$response = $this->Client->call('supervisor.startProcessGroup', [$process_name, $wait]); 
		if($response[0]['description'] == 'OK'){
			return true; 
		}

		return false; 
	}

	/**
	 * adds the process to the group to be started
	 *
	 * @return boolean
	 # @date 2014-10-10
	 **/
	public function addProcessGroup($process_name)
	{
		// check to see if the process is already added
		$processes = $this->getAllProcessInfo();
		
		foreach($processes as $process) {
			if($process['name'] == $process_name) {
				return true;
			}
		}

		return $this->Client->call('supervisor.addProcessGroup', [$process_name]);
	}

	/**
	 * Stops a Supervisor Process
	 *
	 * @return boolean
	 # @date 2014-10-10
	 **/
	public function stopProcess($process_name, $wait = false)
	{
		$result =  $this->Client->call('supervisor.stopProcessGroup', [$process_name, $wait]);

		if($result[0]['description'] != 'OK') {
			return false;
		}

		return true;
	}

	/**
	 * Removes the Supervisor Process from the group of available configs
	 *
	 * @return boolean	
	 # @date 2014-10-10
	 **/
	public function removeProcessGroup($process_name)
	{
		return $this->Client->call('supervisor.removeProcessGroup', [$process_name]);
	}

	/**
	 * Get info about all available process configurations. 
	 *
	 * @return array
	 # @date 2014-10-07
	 **/
	public function getAllConfig()
	{
		return $this->Client->call('supervisor.getAllConfigInfo');
	}

	/**
	 * reloads the configuration files and returns an array 
	 *
	 * @return array
	 # @date 2014-10-07
	 **/
	public function reloadConfig()
	{
		$config = $this->Client->call('supervisor.reloadConfig'); 

		return $config[0][0]; 
	}

	public function getProcessInfo($process_name)
	{
		return $this->Client->call('supervisor.getProcessInfo', [$process_name]);
	}

	/**
	 * Gets all the processes info
	 *
	 * @return array
	 # @date 2014-10-07
	 **/
	public function getAllProcessInfo()
	{
		return $this->Client->call('supervisor.getAllProcessInfo'); 
	}

	/**
	 * Checks the state of the connection 
	 *
	 * @return boolean
	 # @date 2014-10-07
	 **/
	public function checkConnection()
	{
		$result = $this->getState(); 
		return (boolean) $result['statecode']; 
	}

	/**
	 * Reads the main log of the supervisor process
	 *
	 * @return string
	 # @date 2014-10-07
	 **/
	public function readLog($offset = 0, $length = 100)
	{
		return $this->Client->call('supervisor.readLog', array($offset, $length)); 
	}

	/**
	 * Returns the identifying string of the supervisord
	 *
	 * @return string identifer
	 # @date 2014-10-07
	 **/
	public function getIdentification()
	{
		return $this->Client->call('supervisor.getIdentification'); 
	}

	/**
	 * Returns the Supervisor PID
	 *
	 * @return integer PID
	 # @date 2014-10-07
	 **/
	public function getPID()
	{
		return $this->Client->call('supervisor.getPID'); 
	}
	
	/**
	 * returns the state of the connection
	 *
	 * @return array
	 # @date 2014-10-07
	 **/
	public function getState()
	{
		return $this->Client->call('supervisor.getState'); 
	}

	/**
	 * returns all of the supervisor methods
	 *
	 * @return array
	 # @date 2014-10-07
	 **/
	public function listMethods()
	{
		return $this->Client->call('system.listMethods');
	}
	
	/**
	 * returns the method documentation
	 *
	 * @return string
	 # @date 2014-10-07
	 **/
	public function methodHelp($method_name)
	{
		return $this->Client->call('system.methodHelp', [$method_name]);
	}
}
