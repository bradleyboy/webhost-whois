<?php

class WebhostWhois
{
	public $key = 'unknown';
	private $results;

	// For magic methods
	// Ex. isMediaTempleGs()
	public function __call($name, $parameters)
	{
		$original = $name;
		$name = preg_replace_callback('/^is([A-Z])/', create_function('$matches', 'return strtolower($matches[1]);'), $name);
		$name = preg_replace_callback('/([A-Z])/', create_function('$matches', 'return \'-\' . strtolower($matches[1]);'), $name);

		if (isset($this->results[$name]))
		{
			return (bool) $this->results[$name];
		}
		else
		{
			throw new ErrorException('WebhostWhois class does not have method ' . $original);
		}
	}

	function __construct()
	{
		// Used for several tests
		// Many hosts can be identified by the domain listed
		$uname = php_uname();

		// Tests for each webhost go here. Each test should evaluate to a boolean.
		// Keep tests in alphabetical order by key.
		$results = array(
			'bluehost'        => strpos($uname, 'hostmonster.com ') !== false,
			'dreamhost'       => isset($_SERVER['DH_USER']),
			'go-daddy'        => strpos($uname, 'secureserver.net') !== false,
			'in-motion'       => strpos($uname, '.inmotionhosting.com') !== false,
			'media-temple-gs' => isset($_SERVER['ACCESS_DOMAIN']) && preg_match('/\.gridserver\.com$/', $_ENV['ACCESS_DOMAIN']) === 1,
			'ovh'             => strpos($uname, '.ovh.net ') !== false,
			'rackspace-cloud' => strpos($uname, 'stabletransit.com ') !== false,
			'site5'           => strpos($uname, '.accountservergroup.com ') !== false,
		);

		// Separate definitions for hosts that can only be detected via DNS nameservers.
		// Should try as much as possible not to do this, as it is slower.
		// These will only be checked if none of the $results pass.
		// Test will pass if any of the supplied nameservers are found in the DNS lookup.
		$dns = array(
			'media-temple-dv' => array('ns1.mediatemple.net', 'ns2.mediatemple.net'),
		);

		foreach($this->results as $key => $passes)
		{
			if ($passes === true)
			{
				$this->key = $key;
				break;
			}
		}

		if ($this->key === 'unknown')
		{
			$dnsInfo = dns_get_record($_SERVER['HTTP_HOST'], DNS_NS);
			$ns = array();
			foreach($dnsInfo as $info)
			{
				$ns[] = $info['target'];
			}

			foreach($dns as $key => $nameServers)
			{
				if ($this->key === 'unknown' && count(array_intersect($nameServers, $ns)) > 0)
				{
					$this->key = $key;
					$this->results[$key] = true;
				}
				else
				{
					$this->results[$key] = false;
				}
			}
		}
		else
		{
			foreach($dns as $key => $nameServers)
			{
				$this->results[$key] = false;
			}
		}
	}
}
