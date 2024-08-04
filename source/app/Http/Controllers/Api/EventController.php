<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EventController extends Controller
{

    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include');
        if (! $include) {
            return false;
        }
        $relations = array_map('trim', explode(',', $include));
        return in_array($relation, $relations);
    }

    public function index(): ResourceCollection
    {
        $query = Event::query();
//        $relations = ['user', 'attendees', 'attendees.user'];
//
//        foreach ($relations as $relation) {
//            $query->when($this->shouldIncludeRelation($relation), fn($q) => $q->with($relation));
//        }


        return EventResource::collection($query->latest()->with('user')->paginate(25));
    }

    public function store(EventRequest $request): EventResource
    {
        $data = $request->validated();
        return new EventResource(Event::query()->create([...$data, 'user_id' => 1]));
    }

    public function show(Event $event): EventResource
    {
        return new EventResource($event);
    }

    public function update(EventRequest $request, Event $event): EventResource
    {
        $data = $request->validated();
        $event->update($data);
        return new EventResource($event);
    }

    public function destroy(Event $event): JsonResponse
    {
        $event->delete();
        return response()->json(status: 204);
    }
}
