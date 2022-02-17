<?php

namespace App\Http\Controllers;

use App\Http\Problems\NotFoundProblem;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    private static int $DEFAULT_N_PAGE_RECORDS = 100;

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->query('n_page_records', self::$DEFAULT_N_PAGE_RECORDS);
        return Auth::user()->authors()->paginate($pageSize);
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
            'name' => ['required'],
            'birthdate' => ['date', 'before:today', 'nullable'],
            'birthplace' => ['max:255', 'nullable'],
        ]);

        Author::create([
            ...$validatedData,
            'user_id' => Auth::id()
        ]);

        return response()->json('', 204);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Author $author)
    {
        if ($author->user_id === Auth::id()) {
            return response()->json($author);
        }
        return response()->json(
            (new NotFoundProblem(request(), __('Author error')))->toArray(),
            NotFoundProblem::$status
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Author $author)
    {
        if ($author->user_id === Auth::id()) {
            $validatedData = $request->validate([
                'name' => ['nullable'],
                'birthdate' => ['date', 'before:today', 'nullable'],
                'birthplace' => ['max:255', 'nullable'],
            ]);

            Author::updateOrCreate(
                $author->toArray(),
                $validatedData
            );

            return response()->json('', 204);
        }

        return response()->json(
            (new NotFoundProblem(request(), __('Author error')))->toArray(),
            NotFoundProblem::$status
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Author $author)
    {
        if ($author->user_id === Auth::id()) {
            $author->delete();

            return response()->json('', 204);
        }

        return response()->json(
            (new NotFoundProblem(request(), __('Author error')))->toArray(),
            NotFoundProblem::$status
        );
    }
}
