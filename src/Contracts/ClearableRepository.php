<?php

namespace Aqayepardakht\Logger\Contracts;

interface ClearableRepository
{
    /**
     * Clear all of the entries.
     *
     * @return void
     */
    public function clear();
}
