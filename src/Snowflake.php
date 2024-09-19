<?php

namespace Qh\LaravelSnowflake;

class Snowflake
{
    protected const DEFAULT_EPOCH_DATETIME = '2024-01-01 00:00:00';

    protected const ID_BITS = 63;

    protected const WORKER_ID_BITS = 5;

    protected const DATACENTER_ID_BITS = 5;

    protected const SEQUENCE_BITS = 12;

    protected int $epoch;

    protected int $lastTimestamp;

    protected int $sequence = 0;

    private int $datacenterId;

    private int $workerId;

    public function __construct(
        ?int $timestamp = null,
        int $workerId = 1,
        int $datacenterId = 1,
    ) {
        if ($timestamp === null) {
            $timestamp = strtotime(self::DEFAULT_EPOCH_DATETIME);
        }

        $this->epoch = $timestamp * 1000;
        $this->workerId = $workerId;
        $this->datacenterId = $datacenterId;
        $this->lastTimestamp = $this->epoch;
    }

    public function generate(): int
    {
        $currentTime = $this->timestamp();
        $maxSequence = (1 << self::SEQUENCE_BITS) - 1;

        while (($sequenceId = $this->makeSequenceId($currentTime)) > $maxSequence) {
            usleep(1);
            $currentTime = $this->timestamp();
        }

        $this->lastTimestamp = $currentTime;

        return $this->toSnowflakeId($currentTime - $this->epoch, $sequenceId);
    }

    public function id(): int
    {
        return $this->generate();
    }

    public function nextId(): int
    {
        return $this->generate();
    }

    public function decode(int $id): array
    {
        $id = decbin($id);

        $datacenterIdLeftShift = self::WORKER_ID_BITS + self::SEQUENCE_BITS;
        $timestampLeftShift = self::DATACENTER_ID_BITS + self::WORKER_ID_BITS + self::SEQUENCE_BITS;

        $binaryTimestamp = substr($id, 0, -$timestampLeftShift);
        $binarySequence = substr($id, -self::SEQUENCE_BITS);
        $binaryWorkerId = substr($id, -$datacenterIdLeftShift, self::WORKER_ID_BITS);
        $binaryDatacenterId = substr($id, -$timestampLeftShift, self::DATACENTER_ID_BITS);
        $timestamp = bindec($binaryTimestamp);
        $datetime = date('c', ((int) (($timestamp + $this->epoch) / 1000) | 0));

        return [
            'binary_length' => strlen($id),
            'binary' => $id,
            'binary_timestamp' => $binaryTimestamp,
            'binary_sequence' => $binarySequence,
            'binary_worker_id' => $binaryWorkerId,
            'binary_datacenter_id' => $binaryDatacenterId,
            'timestamp' => $timestamp,
            'sequence' => bindec($binarySequence),
            'worker_id' => bindec($binaryWorkerId),
            'datacenter_id' => bindec($binaryDatacenterId),
            'epoch' => $this->epoch,
            'datetime' => $datetime,
        ];
    }

    protected function makeSequenceId(int $currentTime, ?int $max = null): int
    {
        $max = $max ?? (1 << self::SEQUENCE_BITS) - 1;

        if ($this->lastTimestamp === $currentTime) {
            $this->sequence = $this->sequence + 1;

            return $this->sequence;
        }

        $this->sequence = mt_rand(0, $max);

        $this->lastTimestamp = $currentTime;

        return $this->sequence;
    }

    protected function toSnowflakeId(int $currentTime, int $sequenceId): int
    {
        $workerIdLeftShift = self::SEQUENCE_BITS;
        $datacenterIdLeftShift = self::WORKER_ID_BITS + self::SEQUENCE_BITS;
        $timestampLeftShift = self::DATACENTER_ID_BITS + self::WORKER_ID_BITS + self::SEQUENCE_BITS;

        return ($currentTime << $timestampLeftShift)
            | ($this->datacenterId << $datacenterIdLeftShift)
            | ($this->workerId << $workerIdLeftShift)
            | ($sequenceId);
    }

    protected function timestamp(): int
    {
        return (int) floor(microtime(true) * 1000);
    }
}
