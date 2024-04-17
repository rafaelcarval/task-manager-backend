<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Swagger(
 *     schemes={"https"},
 *     host="mywebsite.com",
 *     basePath="",
 *     @OA\Info(
 *         version="1.0.0",
 *         title="API TASK CALENDAR",
 *         description="Nossa **API** oferece as principais funcionalidades para o fluxo de uma tarefa, como - [CRUD Tarefas](operations/storeOrder) - [Cancelar um pedido](operations/cancelOrder)
 *          CRUD tarefas
 *          CRUD USUÁRIOS",
 *         @OA\Contact(
 *             email="rafael.frotac@gmail.com"
 *         ),
 *     ),
 *     @OA\SecurityScheme(
 *          securityScheme="sanctum",
 *          type="apiKey",
 *          name="Authorization",
 *          in="header"
 *     ),
 *     @OA\Tag(
 *           name="Usuários",
 *           description="API Endpoints of Users"
 *     ),
 *     @OA\Tag(
 *           name="Tarefas",
 *           description="API Endpoints of Jobs"
 *     ),
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
