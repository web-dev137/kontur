<?php

namespace app\models\requests;

use OpenApi\Annotations as QA;

/**
 * @OA\Schema(
 *     title="UserRequest",
 *     required={"login","password"}
 *  ),
 * @QA\Property(
 *     property="login",
 *     type="string",
 *     description="Login",
 *     example="userNew"
 * ),
 * @property string $login
 *
 * @QA\Property(
 *     property="password",
 *     type="string",
 *     description="Password",
 *     example="u123"
 * )
 * @property string $password
 */

class UserRequest{}