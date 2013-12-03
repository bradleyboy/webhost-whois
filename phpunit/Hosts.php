<?php

require dirname(dirname(__FILE__)) . '/WebhostWhois.php';

class Hosts extends PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException ErrorException
	 */
	public function testException()
	{
		$host = new WebhostWhois;
		$host->isSomeHost();
	}

	public function testUnknown()
	{
		$host = new WebhostWhois;
		$this->assertFalse($host->isBluehost());
		$this->assertEquals($host->key, 'unknown');
		$this->assertFalse($host->isMediaTempleGrid());
		$this->assertFalse($host->isMediaTempleDv());
	}

	public function testBluehost()
	{
		$host = new WebhostWhois('Linux host192.hostmonster.com 2.6.32-20130307.60.9.bh6.x86_64 #1 SMP Thu Mar 7 15:58:33 EST 2013 x86_64');
		$this->assertTrue($host->isBluehost());
		$this->assertEquals($host->key, 'bluehost');
		$this->assertFalse($host->isMediaTempleGrid());
		$this->assertFalse($host->isMediaTempleDv());
	}

	public function testDreamhost()
	{
		$host = new WebhostWhois(false, array('DH_USER' => 'bdaily'));
		$this->assertTrue($host->isDreamhost());
		$this->assertEquals($host->key, 'dreamhost');
	}

	public function testGoDaddy()
	{
		$host = new WebhostWhois('Linux p3nlhg633.shr.prod.phx3.secureserver.net 2.6.32-358.18.1.el6.nfsfixes.x86_64 #1 SMP Wed Sep 4 14:07:10 MST 2013 x86_64');
		$this->assertTrue($host->isGoDaddy());
		$this->assertEquals($host->key, 'go-daddy');
	}

	public function testInMotion()
	{
		$host = new WebhostWhois('Linux ecbiz115.inmotionhosting.com 2.6.18-348.16.1.el5 #1 SMP Wed Aug 21 04:00:25 EDT 2013 x86_64');
		$this->assertTrue($host->isInMotion());
		$this->assertEquals($host->key, 'in-motion');
	}

	public function testMediaTempleGrid()
	{
		$host = new WebhostWhois(false, array('ACCESS_DOMAIN' => 's12345.gridserver.com'));
		$this->assertTrue($host->isMediaTempleGrid());
		$this->assertEquals($host->key, 'media-temple-grid');
	}

	public function testMediaTempleDv()
	{
		$host = new WebhostWhois(false, array('HTTP_HOST' => 'slideshowpro.net'));
		$this->assertTrue($host->isMediaTempleDv());
		$this->assertFalse($host->isMediaTempleGrid());
		$this->assertEquals($host->key, 'media-temple-dv');
	}

	public function testOvh()
	{
		$host = new WebhostWhois('Linux webm215.90.ha.ovh.net 3.10.11-mutu-grs-ipv6-64 #1 SMP Tue Sep 24 18:07:54 CEST 2013 x86_64');
		$this->assertTrue($host->isOvh());
		$this->assertEquals($host->key, 'ovh');
	}

	public function testRackspaceCloud()
	{
		$host = new WebhostWhois('Linux php53-v08.wc1.ord1.stabletransit.com 2.6.32.39-grsec-3.mosso5.1.x86_64 #1 SMP Mon May 16 20:37:28 CDT 2011 x86_64');
		$this->assertTrue($host->isRackspaceCloud());
		$this->assertEquals($host->key, 'rackspace-cloud');
	}

	public function testSite5()
	{
		$host = new WebhostWhois('Linux bancroft.accountservergroup.com 3.2.45-grsec #1 SMP Thu May 23 08:37:40 CDT 2013 x86_64');
		$this->assertTrue($host->isSite5());
		$this->assertEquals($host->key, 'site5');
	}
}