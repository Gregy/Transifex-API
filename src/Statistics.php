<?php

/*
 * BabDev Transifex Package
 *
 * (c) Michael Babker <michael.babker@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BabDev\Transifex;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;

/**
 * Transifex API Statistics class.
 *
 * @link http://docs.transifex.com/api/statistics/
 */
class Statistics extends TransifexObject
{
    /**
     * Get statistics on a specified resource.
     *
     * @param string $project  The slug for the project to pull from
     * @param string $resource The slug for the resource to pull from
     * @param string $lang     An optional language code to return data only for a specified language
     *
     * @return ResponseInterface
     */
    public function getStatistics(string $project, string $resource, string $lang = '') : ResponseInterface
    {
        return $this->client->request(
            'GET',
            new Uri("/api/2/project/$project/resource/$resource/stats/$lang"),
            ['auth' => $this->getAuthData()]
        );
    }
}
