<?php

/*
 * This file is part of the tmilos/jose-jwt package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\JoseJwt\Random;

use Tmilos\JoseJwt\Error\JoseJwtException;

class FallbackRandomGenerator implements RandomGenerator
{
    /** @var RandomGenerator|null */
    private $first;

    /** @var RandomGenerator|null */
    private $second;

    /**
     * @param RandomGenerator $first
     * @param RandomGenerator $second
     */
    public function __construct(RandomGenerator $first = null, RandomGenerator $second = null)
    {
        $this->first = $first;
        $this->second = $second;
    }

    /**
     * @return RandomGenerator|null
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * @param RandomGenerator|null $first
     *
     * @return FallbackRandomGenerator
     */
    public function setFirst(RandomGenerator $first = null)
    {
        $this->first = $first;

        return $this;
    }

    /**
     * @return RandomGenerator|null
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * @param RandomGenerator|null $second
     *
     * @return FallbackRandomGenerator
     */
    public function setSecond(RandomGenerator $second = null)
    {
        $this->second = $second;

        return $this;
    }

    /**
     * @param int $bytesLength
     *
     * @return string
     */
    public function get($bytesLength)
    {
        if ($this->first) {
            return $this->first->get($bytesLength);
        } elseif ($this->second) {
            return $this->second->get($bytesLength);
        }

        throw new JoseJwtException('No random generators provided');
    }
}
