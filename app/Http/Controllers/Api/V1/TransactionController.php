<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Transaction",
 *     description="Transaction related operations"
 * )
 */
class TransactionController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\Post(
     *     path="/api/v1/transactions",
     *     tags={"Transaction"},
     *     summary="Add a new transaction",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="type",
     *                 type="string",
     *                 description="Type of the transaction",
     *                 example="income"
     *             ),
     *             @OA\Property(
     *                 property="amount",
     *                 type="number",
     *                 description="Amount of the transaction",
     *                 example=100.50
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 description="Description of the transaction",
     *                 example="Salary payment"
     *             ),
     *             @OA\Property(
     *                 property="date",
     *                 type="string",
     *                 format="date",
     *                 description="Date of the transaction",
     *                 example="2024-09-04"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction created",
     *         @OA\JsonContent(ref="#/components/schemas/Transaction")
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

        $request->merge(['user_id' => $user->id]);

        $request->validate([
            'type' => 'required|string',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $transaction = new Transaction($request->all());
        $transaction->save();

        return response()->json($transaction, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/transactions/{id}",
     *     tags={"Transaction"},
     *     summary="Update a transaction",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the transaction",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="type",
     *                 type="string",
     *                 description="Type of the transaction",
     *                 example="income"
     *             ),
     *             @OA\Property(
     *                 property="amount",
     *                 type="number",
     *                 description="Amount of the transaction",
     *                 example=100.50
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 description="Description of the transaction",
     *                 example="Salary payment"
     *             ),
     *             @OA\Property(
     *                 property="date",
     *                 type="string",
     *                 format="date",
     *                 description="Date of the transaction",
     *                 example="2024-09-04"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction updated",
     *         @OA\JsonContent(ref="#/components/schemas/Transaction")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Transaction not found"
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $this->authorize('update', $transaction);

        $request->validate([
            'type' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        $transaction->update($request->only(['type', 'amount', 'description', 'date']));

        return new TransactionResource($transaction);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/transactions/{id}",
     *     tags={"Transaction"},
     *     summary="Delete a transaction",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the transaction",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Transaction deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Transaction not found"
     *             )
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/transactions",
     *     tags={"Transaction"},
     *     summary="Get all transactions for the authenticated user",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of transactions",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Transaction")
     *         )
     *     )
     * )
     */
    public function show()
    {
        $transactions = Transaction::where('user_id', Auth::id())->get();
        return TransactionResource::collection($transactions);
    }
}
