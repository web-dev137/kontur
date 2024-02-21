<?php

namespace app\models\requests;

use Yii;
use OpenApi\Annotations as QA;

/**
 * @OA\Schema(
 *   schema="ReplyRequest",
 *   required={"reply"}
 *  ),
 * @OA\Property(
 *     property="reply",
 *     type="string",
 *     description="reply",
 *     example="comment1"
 * ),
 * @property string $reply
 */
class ReplyRequest{}