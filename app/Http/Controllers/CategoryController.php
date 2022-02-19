<?php

namespace App\Http\Controllers;

use App\Http\Problems\NotFoundProblem;
use App\Models\Category;
use App\Rules\UniqueNameInUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
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
        return Auth::user()->categories()->paginate($pageSize);
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
            'name' => ['required', 'max:255', new UniqueNameInUser(Auth::user())],
        ]);

        Category::create([
            ...$validatedData,
            'user_id' => Auth::id()
        ]);

        return response()->json('', 204);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        if ($category->user_id === Auth::id()) {
            return response()->json($category);
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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Category $category)
    {
        if (Auth::id() === $category->user_id) {
            $validatedData = $request->validate([
                'name' => ['required', 'max:255'],
            ]);

            Category::updateOrCreate(
                $category->toArray(),
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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        if (Auth::id() === $category->user_id) {
            $category->delete();
            return response()->json('', 204);
        }

        return response()->json(
            (new NotFoundProblem(request(), __('Author error')))->toArray(),
            NotFoundProblem::$status
        );
    }
}
