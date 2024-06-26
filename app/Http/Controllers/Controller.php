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
 *         description="Nossa **API** oferece as principais funcionalidades para o fluxo de uma tarefa. <br/>
Trabalhando com a segurança de autenticação para o input e retorno dos dados, você precisa colocar no campo **Authorization** o token gerado quando efetua o login ",
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
