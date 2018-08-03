<?php
use PHPUnit\Framework\TestCase;
require "index.php";
class IndexTest extends TestCase
{
    public function testIs15()
    {
        $this->assertTrue(is15("123456789012345"));
        $this->assertFalse(is15("AAA"));
    }
    public function testContainW()
    {
        $this->assertTrue(containW("wagon"));
        $this->assertFalse(containW("Zigurat"));
    }
    public function testEndsWithQ()
    {
        $this->assertTrue(endsWithQ("Vidocq"));
        $this->assertFalse(endsWithQ("Groutch"));
    }
    public function testIsBefore2000()
    {
        $date1["im:releaseDate"]["label"]="1999-01-01";
        $date2["im:releaseDate"]["label"]="2000-01-01";
        $this->assertTrue(isBefore2000($date1));
        $this->assertFalse(isBefore2000($date2));
    }
}