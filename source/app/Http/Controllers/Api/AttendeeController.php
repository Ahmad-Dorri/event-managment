<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AttendeeController extends Controller
{

    public function index(Event $event): ResourceCollection
    {
        return AttendeeResource::collection($event->attendees()->latest()->paginate(25));
    }

    public function store(Request $request, Event $event): AttendeeResource
    {
        $attendee = $event->attendees()->create([
            'user_id' => 1,
        ]);

        return new AttendeeResource($attendee);
    }

    public function show(Event $event, string $attendeeId): AttendeeResource
    {
        $attendee = $event->attendees()->findOrFail($attendeeId);
        return new AttendeeResource($attendee);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
