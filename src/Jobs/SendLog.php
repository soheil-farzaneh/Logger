<?php

namespace Aqayepardakht\Logger\Jobs;

use Illuminate\Bus\Queueable;
use Aqayepardakht\Logger\Curl;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $entries;

    public function __construct(collection $entries)
    {
        $this->entries = $entries;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return Curl::execute($this->entries);
    }
}