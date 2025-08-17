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
     * @OA\Items(
     * @OA\Property(property="id", type="integer"),
     * @OA\Property(property="full_name", type="string"),
     * @OA\Property(property="phone_number", type="string"),
     * @OA\Property(property="role", type="string"),
     * @OA\Property(property="email", type="string"),
     * @OA\Property(property="created_at", type="string"),
     * @OA\Property(property="updated_at", type="string"),
     * @OA\Property(
     * property="addresses",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer"),
     * @OA\Property(property="name", type="string", nullable=true),
     * @OA\Property(property="address_line_1", type="string"),
     * @OA\Property(property="address_line_2", type="string", nullable=true),
     * @OA\Property(property="suburb", type="string"),
     * @OA\Property(property="city", type="string"),
     * @OA\Property(property="province", type="string"),
     * @OA\Property(property="postal_code", type="string"),
     * @OA\Property(property="country", type="string"),
     * @OA\Property(property="created_at", type="string"),
     * )
     * )
     * )
     * ),
     * @OA\Property(
     * property="links",
     * type="object",
     * @OA\Property(property="first", type="string", nullable=true, example="http://localhost/api/v1/user?page=1"),
     * @OA\Property(property="last", type="string", nullable=true, example="http://localhost/api/v1/user?page=3"),
     * @OA\Property(property="prev", type="string", nullable=true),
     * @OA\Property(property="next", type="string", nullable=true, example="http://localhost/api/v1/user?page=2"),
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
     * @OA\JsonContent(
     * required={"first_name","last_name","phone_number","email","role_id"},
     * @OA\Property(property="first_name", type="string", example="John"),
     * @OA\Property(property="last_name", type="string", example="Doe"),
     * @OA\Property(property="phone_number", type="string", example="0711234567"),
     * @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     * @OA\Property(property="role_id", type="integer", example=1),
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="User created successfully",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer"),
     * @OA\Property(property="full_name", type="string"),
     * @OA\Property(property="phone_number", type="string"),
     * @OA\Property(property="role", type="string"),
     * @OA\Property(property="email", type="string"),
     * @OA\Property(property="created_at", type="string"),
     * @OA\Property(property="updated_at", type="string"),
     * @OA\Property(property="addresses", type="array", @OA\Items(type="object")),
     * )
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation errors",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The given data was invalid."),
     * @OA\Property(
     * property="errors",
     * type="object",
     * @OA\Property(property="first_name", type="array", @OA\Items(type="string", example="The first name field is required.")),
     * @OA\Property(property="last_name", type="array", @OA\Items(type="string", example="The last name field is required.")),
     * @OA\Property(property="phone_number", type="array", @OA\Items(type="string", example="The phone number field is required.")),
     * @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email field is required.")),
     * @OA\Property(property="role_id", type="array", @OA\Items(type="string", example="The role id field is required.")),
     * )
     * )
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
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="full_name", type="string", example="Contact Roux"),
     * @OA\Property(property="phone_number", type="string", example="0363079825"),
     * @OA\Property(property="role", type="string", example="Admin"),
     * @OA\Property(property="email", type="string", example="madonsela.john@example.org"),
     * @OA\Property(property="created_at", type="string", example="23 hours ago"),
     * @OA\Property(property="updated_at", type="string", example="7 hours ago"),
     * @OA\Property(
     * property="addresses",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="name", type="string", example="Home"),
     * @OA\Property(property="address_line_1", type="string", example="983 Lush Dale"),
     * @OA\Property(property="address_line_2", type="string", example="7547 Johann Glens Street"),
     * @OA\Property(property="suburb", type="string", example="Mangaung"),
     * @OA\Property(property="city", type="string", example="Mbombela (Nelspruit)"),
     * @OA\Property(property="province", type="string", example="Mpumalanga"),
     * @OA\Property(property="postal_code", type="string", example="4187"),
     * @OA\Property(property="country", type="string", example="South Africa"),
     * @OA\Property(property="created_at", type="string", example="23 hours ago")
     * )
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
     * @OA\JsonContent(
     * @OA\Property(property="first_name", type="string", example="Claudia"),
     * @OA\Property(property="last_name", type="string", example="Jones"),
     * @OA\Property(property="phone_number", type="string", example="+27(18)8323988"),
     * @OA\Property(property="email", type="string", format="email", example="maluleka.francis@example.org"),
     * @OA\Property(property="role_id", type="integer", example=1)
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="User updated successfully",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=6),
     * @OA\Property(property="full_name", type="string", example="Claudia Jones"),
     * @OA\Property(property="phone_number", type="string", example="+27(18)8323988"),
     * @OA\Property(property="role", type="string", example="Driver"),
     * @OA\Property(property="email", type="string", example="maluleka.francis@example.org"),
     * @OA\Property(property="created_at", type="string", example="23 hours ago"),
     * @OA\Property(property="updated_at", type="string", example="23 hours ago"),
     * @OA\Property(
     * property="addresses",
     * type="array",
     * @OA\Items(
     * type="object",
     * @OA\Property(property="id", type="integer", example=11),
     * @OA\Property(property="name", type="string", example="School"),
     * @OA\Property(property="address_line_1", type="string", example="8949 Xhosa Avenue Heights"),
     * @OA\Property(property="address_line_2", type="string", example="6801 Marais Squares Road"),
     * @OA\Property(property="suburb", type="string", example="Willows"),
     * @OA\Property(property="city", type="string", example="Emalahleni (Witbank)"),
     * @OA\Property(property="province", type="string", example="Gauteng"),
     * @OA\Property(property="postal_code", type="string", example="0935"),
     * @OA\Property(property="country", type="string", example="South Africa"),
     * @OA\Property(property="created_at", type="string", example="23 hours ago")
     * )
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation errors",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The first name field is required. (and 4 more errors)"),
     * @OA\Property(
     * property="errors",
     * type="object",
     * @OA\Property(property="first_name", type="array", @OA\Items(type="string", example="The first name field is required.")),
     * @OA\Property(property="last_name", type="array", @OA\Items(type="string", example="The last name field is required.")),
     * @OA\Property(property="phone_number", type="array", @OA\Items(type="string", example="The phone number field is required.")),
     * @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email field is required.")),
     * @OA\Property(property="role_id", type="array", @OA\Items(type="string", example="The role id field is required."))
     * )
     * )
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
