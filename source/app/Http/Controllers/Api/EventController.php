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
    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection
    {
        return EventResource::collection(Event::query()->latest()->paginate(25));
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
