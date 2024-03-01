<?php

namespace App\Tests\Service;

class EventMock
{

    public function __construct(
        public $uid,
        public $dtstart,
        public $dtend,
        public $summary
    ) {
    }
}
