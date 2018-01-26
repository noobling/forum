<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 26/01/2018
 * Time: 12:04 AM
 */

namespace App;


trait RecordsActivity
{
    /**
     * Boot the trait
     */
    protected static function bootRecordsActivity()
    {
        // Don't record any activity if the user is not signed in
        if (auth()->guest()) return;

        // Start recording activity for the given events
        foreach (static::getEventsToRecord() as $event) {
            static::$event(function($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function($model) {
            $model->activity()->delete();
        });
    }

    /**
     * Fetch the activity relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    /**
     * Fetch all the events which you want to record activity for
     *
     * @return array
     */
    protected static function getEventsToRecord()
    {
        return ['created'];
    }

    /**
     * Persist the event in to the activities table
     *
     * @param $event
     */
    public function recordActivity($event)
    {
        Activity::create([
            'type' => $this->getActivity($event),
            'user_id' => auth()->id(),
            'subject_id' => $this->id,
            'subject_type' => get_class($this)
        ]);
    }

    /**
     * Determine the activity
     *
     * @param $event
     * @return string
     */
    public function getActivity($event)
    {
        return $event . '_' . strtolower((new \ReflectionClass($this))->getShortName());
    }
}