<?php
/**
 * @package     BabDev.UnitTest
 * @subpackage  HTTP
 *
 * @copyright   Copyright (C) 2012-2013 Michael Babker. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Test class for BDHttpTransport classes.
 *
 * @package     BabDev.UnitTest
 * @subpackage  HTTP
 * @since       1.0
 */
class BDHttpTransportTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var    JRegistry  Options for the BDHttpTransport object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    string  The URL string for the HTTP stub.
	 * @since  1.0
	 */
	protected $stubUrl;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		$this->options = $this->getMock('JRegistry', array('get', 'set'));

		if (!defined('BDTEST_HTTP_STUB') && getenv('BDTEST_HTTP_STUB') == '')
		{
			$this->markTestSkipped('The BDHttpTransport test stub has not been configured');
		}
		else
		{
			$this->stubUrl = defined('BDTEST_HTTP_STUB') ? BDTEST_HTTP_STUB : getenv('BDTEST_HTTP_STUB');
		}
	}

	/**
	 * Data provider for the request test methods.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function transportProvider()
	{
		return array(
			'stream' => array('BDHttpTransportStream'),
			'curl'   => array('BDHttpTransportCurl'),
			'socket' => array('BDHttpTransportSocket')
		);
	}

	/**
	 * Tests the request method with a get request
	 *
	 * @param   array  $transportClass  The class to test
	 *
	 * @return  void
	 *
	 * @dataProvider  transportProvider
	 * @since         1.0
	 */
	public function testRequestGet($transportClass)
	{
		$transport = new $transportClass($this->options);

		$response = $transport->request('get', new JUri($this->stubUrl));

		$body = json_decode($response->body);

		$this->assertThat(
			$response->code,
			$this->equalTo(200)
		);

		$this->assertThat(
			$body->method,
			$this->equalTo('GET')
		);
	}

	/**
	 * Tests the request method with a get request with a bad domain
	 *
	 * @param   string  $transportClass  The transport class to test
	 *
	 * @return  void
	 *
	 * @dataProvider       transportProvider
	 * @expectedException  RuntimeException
	 * @since              1.0
	 */
	public function testBadDomainRequestGet($transportClass)
	{
		$transport = new $transportClass($this->options);
		$response = $transport->request('get', new JUri('http://joomla.babdev.com'));
	}

	/**
	 * Tests the request method with a get request for non existant url
	 *
	 * @param   array  $transportClass  The class to test
	 *
	 * @return  void
	 *
	 * @dataProvider  transportProvider
	 * @since         1.0
	 */
	public function testRequestGet404($transportClass)
	{
		$transport = new $transportClass($this->options);
		$response = $transport->request('get', new JUri($this->stubUrl . ':80'));
	}

	/**
	 * Tests the request method with a put request
	 *
	 * @param   array  $transportClass  The class to test
	 *
	 * @return  void
	 *
	 * @dataProvider  transportProvider
	 * @since         1.0
	 */
	public function testRequestPut($transportClass)
	{
		$transport = new $transportClass($this->options);

		$response = $transport->request('put', new JUri($this->stubUrl));

		$body = json_decode($response->body);

		$this->assertThat(
			$response->code,
			$this->equalTo(200)
		);

		$this->assertThat(
			$body->method,
			$this->equalTo('PUT')
		);
	}

	/**
	 * Tests the request method with a post request and array data
	 *
	 * @param   array  $transportClass  The class to test
	 *
	 * @return  void
	 *
	 * @dataProvider  transportProvider
	 * @since         1.0
	 */
	public function testRequestPost($transportClass)
	{
		$transport = new $transportClass($this->options);

		$response = $transport->request('post', new JUri($this->stubUrl . '?test=okay'), array('key' => 'value'));

		$body = json_decode($response->body);

		$this->assertThat(
			$response->code,
			$this->equalTo(200)
		);

		$this->assertThat(
			$body->method,
			$this->equalTo('POST')
		);

		$this->assertThat(
			$body->post->key,
			$this->equalTo('value')
		);
	}

	/**
	 * Tests the request method with a post request and scalar data
	 *
	 * @param   array  $transportClass  The class to test
	 *
	 * @return  void
	 *
	 * @dataProvider  transportProvider
	 * @since         1.0
	 */
	public function testRequestPostScalar($transportClass)
	{
		$transport = new $transportClass($this->options);

		$response = $transport->request('post', new JUri($this->stubUrl . '?test=okay'), 'key=value');

		$body = json_decode($response->body);

		$this->assertThat(
			$response->code,
			$this->equalTo(200)
		);

		$this->assertThat(
			$body->method,
			$this->equalTo('POST')
		);

		$this->assertThat(
			$body->post->key,
			$this->equalTo('value')
		);
	}
}
