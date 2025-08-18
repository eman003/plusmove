<?php

namespace App\Documentation\Schemas;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     type="object",
 *     description="User Resource",
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          example=340
 *      ),
 *      @OA\Property(
 *          property="full_name",
 *          type="string",
 *          example="Themba Zwane"
 *      ),
 *      @OA\Property(
 *          property="phone_number",
 *          type="string",
 *          example="0711234567"
 *      ),
 *      @OA\Property(
 *          property="role",
 *          type="string",
 *          example="Driver"
 *      ),
 *      @OA\Property(
 *          property="email",
 *          type="string",
 *          format="email",
 *          example="themba.zwane@example.com"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          type="string",
 *          example="5 years ago"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          type="string",
 *
 *      ),
 *      @OA\Property(
 *          property="addresses",
 *          type="array",
 *          @OA\Items(
 *              @OA\Property(
 *                  property="id",
 *                  type="integer",
 *                  example=11
 *              ),
 *              @OA\Property(
 *                  property="name",
 *                  type="string",
 *                  nullable=true,
 *                  example="Home"
 *              ),
 *              @OA\Property(
 *                  property="address_line_1",
 *                  type="string",
 *                  example="983 Lush Dale"
 *              ),
 *              @OA\Property(
 *                  property="address_line_2",
 *                  type="string",
 *                  nullable=true,
 *                  example="7547 Johann Glens Street"
 *              ),
 *              @OA\Property(
 *                  property="suburb",
 *                  type="string",
 *                  example="Mangaung"
 *              ),
 *              @OA\Property(
 *                  property="city",
 *                  type="string",
 *                  example="Johannesburg"
 *              ),
 *              @OA\Property(
 *                  property="province",
 *                  type="string",
 *                  example="Gauteng"
 *              ),
 *              @OA\Property(
 *                  property="postal_code",
 *                  type="integer",
 *                  example=2000
 *              ),
 *              @OA\Property(
 *                  property="country",
 *                  type="string",
 *                  example="South Africa"
 *              ),
 *              @OA\Property(
 *                  property="created_at",
 *                  type="string",
 *                  example="1 day ago"
 *              ),
 *          )
 *      )
 * )
 *
 * @OA\Schema(
 *     schema="UserRequest",
 *     title="User Request",
 *     type="object",
 *     description="User creation/update request",
 *     required={"first_name","last_name","phone_number","email","role_id"},
 *     @OA\Property(
 *          property="first_name",
 *          type="string",
 *          example="Bongani"
 *      ),
 *      @OA\Property(
 *          property="last_name",
 *          type="string",
 *          example="Nkosi"
 *      ),
 *      @OA\Property(
 *          property="phone_number",
 *          type="string",
 *          example="0711234567"
 *      ),
 *      @OA\Property(
 *          property="email",
 *          type="string",
 *          format="email",
 *          example="bongani.nkosi@example.com"
 *      ),
 *      @OA\Property(
 *          property="role_id",
 *          type="integer",
 *          example=1
 *      ),
 * )
 *
 * @OA\Schema(
 *     schema="UserValidationErrors",
 *     title="Validation errors",
 *     type="object",
 *     description="Validation errors of the user creation/update request",
 *     @OA\Property(
 *          property="message",
 *          type="string",
 *          example="The given data was invalid."
 *     ),
 *     @OA\Property(
 *          property="errors",
 *          type="object",
 *          @OA\Property(
 *              property="first_name",
 *              type="array",
 *              @OA\Items(
 *                  type="string",
 *                  example="The first name field is required."
 *              )
 *          ),
 *          @OA\Property(
 *              property="last_name",
 *              type="array",
 *              @OA\Items(
 *                  type="string",
 *                  example="The last name field is required."
 *              )
 *          ),
 *          @OA\Property(
 *              property="phone_number",
 *              type="array",
 *              @OA\Items(
 *                  type="string",
 *                  example="The phone number field is required."
 *              )
 *          ),
 *          @OA\Property(
 *              property="email",
 *              type="array",
 *              @OA\Items(
 *                  type="string",
 *                  example="The email field is required."
 *              )
 *          ),
 *          @OA\Property(
 *              property="role_id",
 *              type="array",
 *              @OA\Items(
 *                  type="string",
 *                  example="The role id field is required."
 *              )
 *          ),
 *      )
 * )
 * @OA\Schema(
 *      schema="PaginationLinks",
 *      title="Pagination Links",
 *      type="object",
 *      @OA\Property(
 *          property="first",
 *          type="string",
 *          nullable=true,
 *          example="http://localhost/api/v1/user?page=1"
 *      ),
 *      @OA\Property(
 *          property="last",
 *          type="string",
 *          nullable=true,
 *          example="http://localhost/api/v1/user?page=3"
 *      ),
 *      @OA\Property(
 *          property="prev",
 *          type="string",
 *          nullable=true,
 *          example=null
 *      ),
 *      @OA\Property(
 *          property="next",
 *          type="string",
 *          nullable=true,
 *          example="http://localhost/api/v1/user?page=2"
 *      ),
 *  )
 *
 */
class UserSchema
{

}
