<?php

declare(strict_types=1);

namespace Ttskch\Csvf;

use PHPUnit\Framework\TestCase;

class CsvfTest extends TestCase
{
    /**
     * @var Csvf
     */
    protected $csvf;

    protected function setUp() : void
    {
        $this->csvf = new Csvf;
    }

    public function testIsInstanceOfCsvf() : void
    {
        $actual = $this->csvf;
        $this->assertInstanceOf(Csvf::class, $actual);
    }
}
