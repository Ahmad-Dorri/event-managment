<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationShips;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AttendeeController extends Controller
{
    use CanLoadRelationShips;
    private array $relations = ['user'];
    public function index(Event $event): ResourceCollection
    {
        return AttendeeResource::collection($this->loadRelationships($event->attendees()->latest())->paginate(25));
    }

    public function store(Event $event): AttendeeResource
    {
        $attendee = $event->attendees()->create([
            'user_id' => 1,
        ]);

        return new AttendeeResource($attendee);
    }

    public function show(Event $event, string $id): AttendeeResource
    {
        $attendee = $this->loadRelationships($event->attendees()->findOrFail($id));
        return new AttendeeResource($attendee);
    }

    public function destroy(Event $event, string $id): JsonResponse
    {
        $event->attendees()->findOrFail($id)->delete();
        return response()->json(status: 204);
    }
}
