<?php

namespace App\Http\Controllers;

use App\Http\Problems\NotFoundProblem;
use App\Models\Book;
use App\Rules\UniqueBookInUser;
use App\Rules\UniqueNameInUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    private static int $DEFAULT_N_PAGE_RECORDS = 100;

    private static function makeNotFoundResponse(Book $book) {
        return response()->json(
            (new NotFoundProblem(
                request(),
                __('messages.not_found.author', ['name' => $book->name])
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
        return Auth::user()->books()->paginate($pageSize);
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
            'name' => ['required', 'max:255'],
            'description' => ['nullable'],
            'purchase_date' => [
                'nullable',
                'before:today',
                new UniqueBookInUser(Auth::user(), $request->name)
            ],
            'cover_type' => ['nullable', 'in:softcover,hardcover_casewrap,hardcover_dust_jacket'],
            'location_id' => ['required']
        ]);

        Book::create([
            ...$validatedData,
            'user_id' => Auth::id()
        ]);

        return response()->json('', 204);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Book $book)
    {
        if ($book->user_id === Auth::id()) {
            return response()->json($book);
        }
        return self::makeNotFoundResponse($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Book $book)
    {
        if ($book->user_id === Auth::id()) {
            $validatedData = $request->validate([
                'name' => ['required', 'max:255'],
                'description' => ['nullable'],
                'purchase_date' => [
                    'nullable',
                    'before:today',
                    new UniqueBookInUser(Auth::user(), $request->name)
                ],
                'cover_type' => ['nullable', 'in:softcover,hardcover_casewrap,hardcover_dust_jacket'],
                'location_id' => ['required']
            ]);

            Book::Create(
                $book->toArray(),
                $validatedData
            );

            return response()->json('', 204);
        }

        return self::makeNotFoundResponse($book);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Book $book)
    {
        if ($book->user_id === Auth::id()) {
            $book->delete();

            return response()->json('', 204);
        }

        return self::makeNotFoundResponse($book);
    }
}
