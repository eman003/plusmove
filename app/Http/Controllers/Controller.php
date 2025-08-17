<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="PlusMove Laravel API",
 *     version="1.0.0",
 *     description="API documentation for PlusMove which handles deliveries."
 * )
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter token in format (Bearer <token>)"
 * )
 */
abstract class Controller
{
    //
}
