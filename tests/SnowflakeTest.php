<?php

namespace Qh\LaravelSnowflake\Tests;

use Qh\LaravelSnowflake\Snowflake;

class SnowflakeTest extends TestCase
{
    private string $epoch = '2024-01-01 00:00:00';

    public function testGenerate()
    {
        $now = strtotime(date('Y-m-d H:i:s'));
        $epoch = strtotime($this->epoch) * 1000;
        $id = $this->createSnowflakeInstance()->generate();

        $timestamp = $id >> 22;
        $timestamp = (int) round(($timestamp + $epoch) / 1000);

        $this->assertTrue($timestamp - $now < 3);
    }

    public function testMultipleGenerateIds()
    {
        $instance = $this->createSnowflakeInstance();

        $ids = [];

        foreach (range(0, 1000) as $_) {
            $ids[] = $instance->nextId();
        }

        $this->assertTrue(array_unique($ids) === $ids);
    }

    protected function createSnowflakeInstance(): Snowflake
    {
        return new Snowflake();
    }
}
