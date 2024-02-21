<?php

namespace app\models\requests;

use Yii;
use OpenApi\Annotations as QA;

/**
 * @OA\Schema(
 *   schema="PostRequest",
 *   required={"post"}
 *  ),
 * @OA\Property(
 *     property="post",
 *     type="string",
 *     description="Text post",
 *     example="post1 first"
 *  ),
 * @property string $post
 *
 *  @OA\Property(
 *     property="status",
 *     type="string",
 *     description="status: public, auth, private",
 *     example="auth"
 *  ),
 * @property string $status
 */

class PostRequest{}