<?php

namespace Aqayepardakht\Logger\Contracts;

interface TerminableRepository
{
    /**
     * Perform any clean-up tasks needed after storing Telescope entries.
     *
     * @return void
     */
    public function terminate();
}
