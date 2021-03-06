<?php

/*
 * BabDev Transifex Package
 *
 * (c) Michael Babker <michael.babker@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BabDev\Transifex\Tests;

use BabDev\Transifex\Statistics;

/**
 * Test class for \BabDev\Transifex\Statistics.
 */
class StatisticsTest extends TransifexTestCase
{
    /**
     * @testdox getStatistics() returns a Response object on a successful API connection
     *
     * @covers  \BabDev\Transifex\Statistics::getStatistics
     * @covers  \BabDev\Transifex\TransifexObject::getAuthData
     *
     * @uses    \BabDev\Transifex\TransifexObject
     */
    public function testGetStatistics()
    {
        $this->prepareSuccessTest();

        (new Statistics($this->options, $this->client))->getStatistics('babdev', 'babdev-transifex');

        $this->validateSuccessTest('/api/2/project/babdev/resource/babdev-transifex/stats/');
    }

    /**
     * @testdox getStatistics() throws a ServerException on a failed API connection
     *
     * @covers  \BabDev\Transifex\Statistics::getStatistics
     * @covers  \BabDev\Transifex\TransifexObject::getAuthData
     *
     * @uses    \BabDev\Transifex\TransifexObject
     *
     * @expectedException \GuzzleHttp\Exception\ServerException
     */
    public function testGetStatisticsFailure()
    {
        $this->prepareFailureTest();

        (new Statistics($this->options, $this->client))->getStatistics('babdev', 'babdev-transifex');
    }
}
