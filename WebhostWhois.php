<?php

class WebhostWhois
{
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

	// Returns the detected webhost's key
	function get()
	{
		foreach($this->results as $key => $passes)
		{
			if ($passes === true)
			{
				return $key;
			}
		}

		return 'unknown';
	}

	function __construct()
	{
		// Used for several tests
		// Many hosts can be identified by the domain listed
		$uname = php_uname();

		// Tests for each webhost go here. Each test should evaluate to a boolean.
		// Keep tests in alphabetical order by key.
		$this->results = array(
			'bluehost'        => strpos($uname, 'hostmonster.com ') !== false,
			'dreamhost'       => isset($_SERVER['DH_USER']),
			'go-daddy'        => strpos($uname, 'secureserver.net') !== false,
			'in-motion'       => strpos($uname, '.inmotionhosting.com') !== false,
			'media-temple-gs' => isset($_SERVER['ACCESS_DOMAIN']) && preg_match('/\.gridserver\.com$/', $_ENV['ACCESS_DOMAIN']) === 1,
			'ovh'             => strpos($uname, '.ovh.net ') !== false,
			'rackspace-cloud' => strpos($uname, 'stabletransit.com ') !== false,
			'site5'           => strpos($uname, '.accountservergroup.com ') !== false,
		);
	}
}
