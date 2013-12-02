<?php

class WebhostWhois
{
	private $results;

	// For magic methods
	// Ex. isMediaTempleGs()
	public function __call($name, $parameters)
	{
		$name = preg_replace_callback('/^is([A-Z])/', create_function('$matches', 'return strtolower($matches[1]);'), $name);
		$name = preg_replace_callback('/([A-Z])/', create_function('$matches', 'return \'-\' . strtolower($matches[1]);'), $name);
		return (bool) $this->results[$name];
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
			'media-temple-gs' => preg_match('/\.gridserver\.com$/', $_ENV['ACCESS_DOMAIN']) === 1,
			'ovh'             => strpos($uname, '.ovh.net ') !== false,
		);
	}
}
