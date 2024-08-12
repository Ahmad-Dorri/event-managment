<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationShips;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EventController extends Controller
{
    use CanLoadRelationShips;

    private array $relations = ['user', 'attendees', 'attendees.user'];
    public function index(): ResourceCollection
    {
        $query = $this->loadRelationships(new Event());
        return EventResource::collection($query->latest()->paginate(25));
    }

    public function store(EventRequest $request): EventResource
    {
        $data = $request->validated();
        return new EventResource($this->loadRelationships(new Event())->create([...$data, 'user_id' => 1]));
    }

    public function show(Event $event): EventResource
    {
        return new EventResource($event);
    }

    public function update(Request $request, Event $event): EventResource
    {
        $data = $request->validate([
            'name' => 'required|min:3',
            'description' => 'required|min:10',
        ]);
        $event->update($data);
        return new EventResource($event);
    }

    public function destroy(Event $event): JsonResponse
    {
        $event->delete();
        return response()->json(status: 204);
    }
}
