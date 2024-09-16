<?php

namespace Qh\LaravelSnowflake;

trait HasSnowflakeIds
{
    public function initializeHasSnowflakeIds(): void
    {
        $this->usesUniqueIds = true;
    }

    public function uniqueIds(): array
    {
        return [$this->getKeyName()];
    }

    public function newUniqueId(): string
    {
        return app(Snowflake::class)->generate();
    }

    public function getIncrementing(): bool
    {
        if (in_array($this->getKeyName(), $this->uniqueIds())) {
            return false;
        }

        return $this->incrementing;
    }
}
