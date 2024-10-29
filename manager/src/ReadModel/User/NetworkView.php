<?php

declare(strict_types=1);

namespace App\ReadModel\User;

class NetworkView
{
    public string $network;
    public string $identity;

    public function __construct(string $network, string $identity)
    {
        $this->network = $network;
        $this->identity = $identity;
    }
}