<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Event;
use Carbon\Carbon;

class NoLocationConflict implements Rule
{
    protected $locationId;
    protected $floorId;
    protected $startDatetime;
    protected $endDatetime;
    protected $eventId; // For update operations
    protected $conflictingEvent;

    /**
     * Create a new rule instance.
     *
     * @param int $locationId
     * @param int|null $floorId
     * @param string $startDatetime
     * @param string|null $endDatetime
     * @param int|null $eventId Event ID to exclude (for updates)
     */
    public function __construct($locationId, $floorId, $startDatetime, $endDatetime = null, $eventId = null)
    {
        $this->locationId = $locationId;
        $this->floorId = $floorId;
        $this->startDatetime = $startDatetime;
        $this->endDatetime = $endDatetime;
        $this->eventId = $eventId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Return true if no location ID provided (will be caught by required validation)
        if (!$this->locationId || !$this->startDatetime) {
            return true;
        }

        try {
            // Parse datetime strings to ensure proper comparison
            $start = Carbon::parse($this->startDatetime);
            $end = $this->endDatetime ? Carbon::parse($this->endDatetime) : null;

            // Build conflict query
            $query = Event::where('location_id', $this->locationId)
                ->where('floor_id', $this->floorId);

            // Check for datetime overlaps
            $query->where(function ($q) use ($start, $end) {
                if ($end) {
                    // Check for overlap:
                    // 1. Existing event starts during new event
                    // 2. Existing event ends during new event
                    // 3. Existing event completely encompasses new event
                    $q->where(function ($subQ) use ($start, $end) {
                        $subQ->whereBetween('event_start_datetime', [$start, $end])
                             ->orWhereBetween('event_end_datetime', [$start, $end])
                             ->orWhere(function ($innerQ) use ($start, $end) {
                                 $innerQ->where('event_start_datetime', '<=', $start)
                                        ->where('event_end_datetime', '>=', $end);
                             });
                    });
                } else {
                    // Only start time provided - check if start time conflicts
                    $q->where('event_start_datetime', '=', $start);
                }
            });

            // Exclude current event when updating
            if ($this->eventId) {
                $query->where('id', '!=', $this->eventId);
            }

            // Check if there's a conflicting event
            $this->conflictingEvent = $query->first();

            return !$this->conflictingEvent;
        } catch (\Exception $e) {
            // If datetime parsing fails, return true to allow other validation rules to catch it
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected location and floor are already booked for another event at the same time. Please choose a different location or schedule.';
    }
}
