<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category; // Assurez-vous que cette ligne est présente
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use OpenApi\Annotations as OA;

// Le reste du code suit...

/**
 * @OA\Tag(
 *     name="Category",
 *     description="Category related operations"
 * )
 */
class CategoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\Post(
     *     path="/api/v1/categories",
     *     tags={"Category"},
     *     summary="Create a new category",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Name of the category",
     *                 example="Groceries"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Invalid data provided"
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $user = $request->user(); // Assuming user is authenticated

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $request->validate([
            'name' => 'required|string|unique:categories,name,NULL,id,user_id,' . $user->id,
        ]);

        $category = new Category([
            'name' => $request->name,
            'user_id' => $user->id
        ]);
        $category->save();

        return response()->json(new CategoryResource($category), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     tags={"Category"},
     *     summary="Get all categories for the authenticated user",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of categories",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Category")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $categories = Category::where('user_id', Auth::id())->get();
        return CategoryResource::collection($categories);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{id}",
     *     tags={"Category"},
     *     summary="Get a category by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category details",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Category not found"
     *             )
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $category = Category::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $this->authorize('view', $category);

        return new CategoryResource($category);
    }
    /**
     * @OA\Put(
     *     path="/api/v1/categories/{id}",
     *     tags={"Category"},
     *     summary="Update a category",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Name of the category",
     *                 example="Utilities"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Category not found"
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $category = Category::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $this->authorize('update', $category);

        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $id . ',id,user_id,' . Auth::id(),
        ]);

        $category->update($request->only('name'));

        return new CategoryResource($category);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/categories/{id}",
     *     tags={"Category"},
     *     summary="Delete a category",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Category deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Category not found"
     *             )
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $category = Category::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $this->authorize('delete', $category);

        $category->delete();

        return response()->json(null, 204);
    }
}
