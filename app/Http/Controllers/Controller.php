<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="GMS Events API Documentation",
 *      description="API documentation for GMS Church Event Management System - Servolution 2025",
 *      @OA\Contact(
 *          email="admin@gms.church"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="sanctum",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Enter your bearer token in the format: Bearer {token}"
 * )
 */
abstract class Controller
{
    //
}
