<?php

namespace App\Http\Controllers;

use App\Http\Problems\NotFoundProblem;
use App\Models\Location;
use App\Rules\UniqueNameInUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    private static int $DEFAULT_N_PAGE_RECORDS = 100;

    private static function makeNotFoundResponse(Location $location) {
        return response()->json(
            (new NotFoundProblem(
                request(),
                __('messages.not_found.location', ['name' => $location->name])
            ))->toArray(),
            NotFoundProblem::$status
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $pageSize = $request->query('n_page_records', self::$DEFAULT_N_PAGE_RECORDS);
        return Auth::user()->locations()->paginate($pageSize);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required',
                'max:255',
                new UniqueNameInUser(Auth::user()->locations())
            ],
        ]);

        Location::create([
            ...$validatedData,
            'user_id' => Auth::id()
        ]);

        return response()->json('', 204);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Location $location)
    {
        if ($location->user_id === Auth::id()) {
            return response()->json($location);
        }

        return self::makeNotFoundResponse($location);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Location $location)
    {
        if (Auth::id() === $location->user_id) {
            $validatedData = $request->validate([
                'name' => ['required', 'max:255'],
            ]);

            Location::updateOrCreate(
                $location->toArray(),
                $validatedData
            );

            return response()->json('', 204);
        }

        return self::makeNotFoundResponse($location);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Location $location)
    {
        if (Auth::id() === $location->user_id) {
            $location->delete();
            return response()->json('', 204);
        }

        return self::makeNotFoundResponse($location);
    }
}
