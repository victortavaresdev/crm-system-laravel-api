<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="CRM System - REST API Documentation",
 *      description="CRM System - REST API Documentation built by Victor Tavares",
 *      @OA\Contact(
 *          email="victortavaresdev@gmail.com"
 *      )
 * ),
 * @OA\SecurityScheme(
 *     type="http",
 *     name="Authorization",
 *     description="Access token obtained from authentication",
 *     in="header",
 *     scheme="bearer",
 *     securityScheme="bearerToken",
 * ),
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Development Server"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
