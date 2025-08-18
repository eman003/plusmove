<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\V1\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/user",
     * summary="Get a list of users",
     * tags={"Users"},
     * @OA\Parameter(
     * name="page",
     * in="query",
     * description="Page number",
     * required=false,
     * @OA\Schema(type="integer", format="int64")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(
     * property="data",
     * type="array",
     * @OA\Items(ref="#/components/schemas/User")
     * ),
     * @OA\Property(
     * property="links",
     * type="object",
     * ref="#/components/schemas/PaginationLinks"
     * ),
     * @OA\Property(
     * property="meta",
     * type="object",
     * @OA\Property(property="current_page", type="integer"),
     * @OA\Property(property="from", type="integer", nullable=true),
     * @OA\Property(property="last_page", type="integer"),
     * @OA\Property(
     * property="links",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="url", type="string", nullable=true),
     * @OA\Property(property="label", type="string"),
     * @OA\Property(property="page", type="integer", nullable=true),
     * @OA\Property(property="active", type="boolean"),
     * )
     * ),
     * @OA\Property(property="path", type="string"),
     * @OA\Property(property="per_page", type="integer"),
     * @OA\Property(property="to", type="integer", nullable=true),
     * @OA\Property(property="total", type="integer"),
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to access this resource."
     * )
     * )
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', User::class);

        $users = User::with('addresses')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return UserResource::collection($users);
    }

    /**
     * @OA\Post(
     * path="/api/v1/user",
     * summary="Create a new user",
     * tags={"Users"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/UserRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="User created successfully",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * ref="#/components/schemas/User"
     * )
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation errors",
     * @OA\JsonContent(ref="#/components/schemas/UserValidationErrors")
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to create a user."
     * )
     * )
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());
        // The system will generate a random secured password for the user and send it to the user's email'
        $password = Str::random(rand(8, 12), );
        $user->update(['password' => Hash::make($password)]);

        $user->notify(new WelcomeNotification($password, $user));

        return new UserResource($user->refresh()->loadMissing('addresses'));
    }

    /**
     * @OA\Get(
     * path="/api/v1/user/{id}",
     * summary="Get a single user by ID",
     * tags={"Users"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the user to retrieve",
     * @OA\Schema(
     * type="integer",
     * format="int64"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="object",
     * ref="#/components/schemas/User"
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to access this user's details."
     * ),
     *   @OA\Response(
     *   response=404,
     *   description="Not Found - The user with the specified ID was not found.",
     *   )
     * )
     */
    public function show(User $user)
    {
        Gate::authorize('view', $user);

        return new UserResource($user->loadMissing('addresses'));
    }

    /**
     * @OA\Put(
     * path="/api/v1/user/{id}",
     * summary="Update an existing user",
     * tags={"Users"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the user to update",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/UserRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="User updated successfully",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="object",
     * ref="#/components/schemas/User"
     * )
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation errors",
     * @OA\JsonContent(ref="#/components/schemas/UserValidationErrors")
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to update this user."
     * ),
     *  @OA\Response(
     *  response=404,
     *  description="Not Found - The user with the specified ID was not found.",
     *  )
     * )
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());

        return new UserResource($user->refresh()->loadMissing('addresses'));
    }

    /**
     * @OA\Delete(
     * path="/api/v1/user/{id}",
     * summary="Delete a user",
     * tags={"Users"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the user to delete",
     * @OA\Schema(type="integer", format="int64")
     * ),
     * @OA\Response(
     * response=200,
     * description="User deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="User deleted successfully")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="User not found"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to delete this user."
     * )
     * )
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', User::class);
        $user->tokens()->delete();
        $user->addresses()->delete();
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
