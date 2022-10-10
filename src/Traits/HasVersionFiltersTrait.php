<?php

namespace Patabugen\MssqlChanges\Traits;

use Illuminate\Console\Command;

trait HasVersionFiltersTrait {

    public int $fromVersion = 1;
    public ?int $toVersion = null;

    public function fromVersion(int $version): self
    {
        $this->fromVersion = $version;
        return $this;
    }

    public function toVersion(?int $version): self
    {
        $this->toVersion = $version;
        return $this;
    }

    public function readVersionFilters(Command $command): self
    {
        if ($command->option('from')) {
            $this->fromVersion($command->option('from'));
        }
        if ($command->option('to')) {
            $this->toVersion($command->option('to'));
        }
        return $this;
    }

}