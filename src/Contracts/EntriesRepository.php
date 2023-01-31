<?php

namespace Aqayepardakht\Logger\Contracts;

use Illuminate\Support\Collection;
use Aqayepardakht\Logger\EntryResult;
use Aqayepardakht\Logger\Storage\EntryQueryOptions;

interface EntriesRepository
{
    /**
     * Return an entry with the given ID.
     *
     * @param  mixed  $id
     * @return \Aqayepardakht\Logger\EntryResult
     */
    public function find($id): EntryResult;

    /**
     * Return all the entries of a given type.
     *
     * @param  string|null  $type
     * @param  \Aqayepardakht\Logger\Storage\EntryQueryOptions  $options
     * @return \Illuminate\Support\Collection|\Aqayepardakht\Logger\EntryResult[]
     */
    public function get($type, EntryQueryOptions $options);

    /**
     * Store the given entries.
     *
     * @param  \Illuminate\Support\Collection|\Aqayepardakht\Logger\IncomingEntry[]  $entries
     * @return void
     */
    public function store(Collection $entries);

    /**
     * Store the given entry updates.
     *
     * @param  \Illuminate\Support\Collection|\Aqayepardakht\Logger\EntryUpdate[]  $updates
     * @return void
     */
    public function update(Collection $updates);

    /**
     * Load the monitored tags from storage.
     *
     * @return void
     */
    public function loadMonitoredTags();

    /**
     * Determine if any of the given tags are currently being monitored.
     *
     * @param  array  $tags
     * @return bool
     */
    public function isMonitoring(array $tags);

    /**
     * Get the list of tags currently being monitored.
     *
     * @return array
     */
    public function monitoring();

    /**
     * Begin monitoring the given list of tags.
     *
     * @param  array  $tags
     * @return void
     */
    public function monitor(array $tags);

    /**
     * Stop monitoring the given list of tags.
     *
     * @param  array  $tags
     * @return void
     */
    public function stopMonitoring(array $tags);
}
