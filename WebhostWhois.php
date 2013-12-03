<?php

class WebhostWhois
{
    public $key = 'unknown';
    private $results;

    // For magic methods
    // Ex. isMediaTempleGrid(), isDreamhost(), etc
    public function __call($name, $parameters)
    {
        $key = preg_replace_callback('/^is([A-Z])/', create_function('$matches', 'return strtolower($matches[1]);'), $name);
        $key = preg_replace_callback('/([A-Z])/', create_function('$matches', 'return \'-\' . strtolower($matches[1]);'), $key);

        if (isset($this->results[$key])) {
            return (bool) $this->results[$key];
        } else {
            throw new BadMethodCallException('WebhostWhois class does not have method ' . $key . '()');
        }
    }

    public function __construct($uname = false, $server = false)
    {
        // Used for several tests
        // Many hosts can be identified by the domain listed
        if (!$uname) {
            $uname = php_uname();
        }

        if ($server) {
            $_SERVER = $server;
        }

        // Tests for each webhost go here. Each test should evaluate to a boolean.
        // Keep tests in alphabetical order by key.
        $this->results = array(
            'bluehost'          => strpos($uname, 'hostmonster.com ') !== false,
            'dreamhost'         => isset($_SERVER['DH_USER']),
            'go-daddy'          => strpos($uname, 'secureserver.net') !== false,
            'in-motion'         => strpos($uname, '.inmotionhosting.com') !== false,
            'media-temple-grid' => isset($_SERVER['ACCESS_DOMAIN']) && preg_match('/\.gridserver\.com$/', $_SERVER['ACCESS_DOMAIN']) === 1,
            'ovh'               => strpos($uname, '.ovh.net ') !== false,
            'rackspace-cloud'   => strpos($uname, 'stabletransit.com ') !== false,
            'site5'             => strpos($uname, '.accountservergroup.com ') !== false,
            'strato'            => strpos($uname, '.stratoserver.net ') !== false,
        );

        // Separate definitions for hosts that can only be detected via DNS nameservers.
        // Should try as much as possible not to do this, as it is slower.
        // These will only be checked if none of the $results pass.
        // Test will pass if any of the supplied nameservers are found in the DNS lookup.
        $dns = array(
            'media-temple-dv' => array('ns1.mediatemple.net', 'ns2.mediatemple.net'),
        );

        $host = array_search(true, $this->results);

        if ($host) {
            $this->key = $host;

            foreach ($dns as $key => $nameServers) {
                $this->results[$key] = false;
            }
        } else {
            $ns = array();

            if (isset($_SERVER['HTTP_HOST'])) {
                $dnsInfo = dns_get_record($_SERVER['HTTP_HOST'], DNS_NS);
                foreach ($dnsInfo as $info) {
                    $ns[] = $info['target'];
                }
            }

            foreach ($dns as $key => $nameServers) {
                if ($this->key === 'unknown' && count(array_intersect($nameServers, $ns)) > 0) {
                    $this->key = $key;
                    $this->results[$key] = true;
                } else {
                    $this->results[$key] = false;
                }
            }
        }
    }
}
