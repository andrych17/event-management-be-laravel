<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EventController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of all events with pagination and filters
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $search = $request->input('search', '');
        $locationId = $request->input('location_id');
        $floorId = $request->input('floor_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $sortBy = $request->input('sort_by', 'event_start_datetime');
        $sortDir = $request->input('sort_dir', 'asc');

        // Validate sort parameters
        $allowedSortFields = ['title', 'location_id', 'floor_id', 'event_start_datetime', 'event_end_datetime', 'created_at'];
        $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'event_start_datetime';
        $sortDir = in_array(strtolower($sortDir), ['asc', 'desc']) ? strtolower($sortDir) : 'asc';

        $query = Event::with(['location', 'floor', 'creator', 'updater']);

        // Search filter (title or description)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Location filter
        if ($locationId) {
            $query->where('location_id', $locationId);
        }

        // Floor filter
        if ($floorId) {
            $query->where('floor_id', $floorId);
        }

        // Date range filter
        if ($dateFrom) {
            $query->whereDate('event_start_datetime', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('event_start_datetime', '<=', $dateTo);
        }

        // Apply sorting
        $query->orderBy($sortBy, $sortDir);

        // Add secondary sort for consistency (if not sorting by datetime)
        if ($sortBy !== 'event_start_datetime') {
            $query->orderBy('event_start_datetime', 'desc');
        }

        $events = $query->paginate($perPage);

        return response()->json($events);
    }

    /**
     * Display today's events in WIB timezone (Task 5: Today's Filter)
     * Can accept optional date parameter to get events for specific date
     */
    public function today(Request $request)
    {
        $date = $request->input('date');

        if ($date) {
            // Validate the date format
            $validator = Validator::make(['date' => $date], [
                'date' => 'date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                return $this->validationError($validator->errors()->toArray());
            }

            // Get events for the specified date
            $events = Event::whereDate('event_start_datetime', $date)
                          ->orderBy('event_start_datetime')
                          ->with(['location', 'floor'])
                          ->get();
        } else {
            // Get today's events
            $date = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $events = Event::today()->with(['location', 'floor'])->get();
        }

        return $this->success("Today's events retrieved successfully", [
            'date' => $date,
            'timezone' => 'Asia/Jakarta (WIB)',
            'events' => $events
        ]);
    }

    /**
     * Store a newly created event (Task 3: CRUD API)
     */
    public function store(StoreEventRequest $request)
    {
        $event = Event::create($request->validated());

        return $this->success('Event created successfully', $event, 201);
    }

    /**
     * Display the specified event (Task 3: CRUD API)
     */
    public function show(Event $event)
    {
        $event->load(['location', 'floor', 'creator', 'updater']);
        return $this->success('Event retrieved successfully', $event);
    }

    /**
     * Update the specified event (Task 3: CRUD API)
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->update($request->validated());

        return $this->success('Event updated successfully', $event);
    }

    /**
     * Remove the specified event (Task 3: CRUD API - soft delete)
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return $this->success('Event deleted successfully');
    }

    /**
     * Get floor availability for a specific date
     * Returns all location-floor combinations with their occupancy status
     * Supports filtering by location_id and floor_id
     */
    public function floorAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'location_id' => 'nullable|exists:configs,id',
            'floor_id' => 'nullable|exists:configs,id',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors()->toArray());
        }

        $date = $request->date;
        $locationId = $request->location_id;
        $floorId = $request->floor_id;

        // Get all active locations
        $locationsQuery = \App\Models\Config::active()->byGroup('Location');

        if ($locationId) {
            $locationsQuery->where('id', $locationId);
        }

        $locations = $locationsQuery->get();

        // Build availability map
        $availability = [];

        foreach ($locations as $location) {
            // Get floors for this location
            $floorsQuery = $location->children()->active();

            if ($floorId) {
                $floorsQuery->where('id', $floorId);
            }

            $floors = $floorsQuery->get();

            foreach ($floors as $floor) {
                // Get events for this location-floor combination on the specified date
                $events = Event::where('location_id', $location->id)
                    ->where('floor_id', $floor->id)
                    ->whereDate('event_start_datetime', $date)
                    ->orderBy('event_start_datetime')
                    ->get();

                $availability[] = [
                    'location_id' => $location->id,
                    'location_name' => $location->value,
                    'floor_id' => $floor->id,
                    'floor_name' => $floor->value,
                    'is_available' => $events->isEmpty(),
                    'events' => $events->map(function ($event) {
                        return [
                            'id' => $event->id,
                            'title' => $event->title,
                            'start_time' => $event->event_start_datetime,
                            'end_time' => $event->event_end_datetime,
                        ];
                    })->values()
                ];
            }
        }

        return $this->success('Floor availability retrieved successfully', [
            'date' => $date,
            'location_id' => $locationId,
            'floor_id' => $floorId,
            'availability' => $availability
        ]);
    }
}
