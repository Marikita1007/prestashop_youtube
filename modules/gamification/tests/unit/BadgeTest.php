<?php

use PHPUnit\Framework\TestCase;

class BadgeTest extends TestCase
{
    /**
     * @var Badge
     */
    private $badge;

    protected function setUp()
    {
        $this->badge = new Badge();
    }

    public function testImgUrl()
    {
        $this->assertStringStartsWith(__PS_BASE_URI__, $this->badge->getBadgeImgUrl());
    }
}
