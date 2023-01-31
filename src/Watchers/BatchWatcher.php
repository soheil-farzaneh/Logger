<?php

namespace Aqayepardakht\Logger\Watchers;

use Illuminate\Bus\Events\BatchDispatched;
use Aqayepardakht\Logger\IncomingEntry;
use Aqayepardakht\Logger\Telescope;

class BatchWatcher extends Watcher
{
    /**
     * Register the watcher.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function register($app)
    {
        $app['events']->listen(BatchDispatched::class, [$this, 'recordBatch']);
    }

    /**
     * Record a job being created.
     *
     * @param  string  $connection
     * @param  string  $queue
     * @param  array  $payload
     * @return \Aqayepardakht\Logger\IncomingEntry|null
     */
    public function recordBatch(BatchDispatched $event)
    {
        if (! Telescope::isRecording()) {
            return;
        }

        $content = array_merge($event->batch->toArray(), [
            'queue' => $event->batch->options['queue'] ?? 'default',
            'connection' => $event->batch->options['connection'] ?? 'default',
            'allowsFailures' => $event->batch->allowsFailures(),
        ]);

        Telescope::recordBatch(
            $entry = IncomingEntry::make(
                $content,
                $event->batch->id
            )->withFamilyHash($event->batch->id)
        );

        return $entry;
    }
}
