<?php

namespace App\Documentation;
/**
 * @OA\Info(
 *     title="PlusMove Laravel API",
 *     version="1.0.0",
 *     description="API documentation for PlusMove which handles deliveries.",
 *     @OA\Contact(
 *         email="fulufhelo.mukwevho6@gmail.com",
 *         name="Fulufhelo Mukwevho"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://plusmove.test/api/v1",
 *     description="Local development server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter token in format (Bearer <token>)"
 * )
 */
class ApiDocumentation
{
    // This class exists only for documentation
}
