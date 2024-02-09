<?php


namespace Api;

use \ApiTester;
use app\fixtures\PostFixture;
use app\fixtures\ReplyFixture;
use app\fixtures\TokenFixture;

class PostCest
{
    public function _before(ApiTester $I)
    {
        $I->haveFixtures([
            'post' => [
                'class' => PostFixture::class,
                'dataFile' => codecept_data_dir().'post.php'
            ],
            'token' => [
                'class' => TokenFixture::class,
                'dataFile' => codecept_data_dir().'token.php'
            ],
            'reply' => [
                'class' => ReplyFixture::class,
                'dataFile' => codecept_data_dir().'reply.php'
            ]
        ]);
    }
    private string $token;

    protected function auth(ApiTester $I)
    {
        $I->sendPOST('/api/login', [
            'login' => 'userNew',
            'password' => '1234ABC',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $I->seeResponseJsonMatchesJsonPath('$.expired');
        $this->token = $I->grabDataFromResponseByJsonPath('$.token')[0];
    }

    public function createUnauthorized(ApiTester $I)
    {
        $I->sendPost('api/post',[
            'post' => 'msg123'
        ]);
    }

    public function getAllPosts(ApiTester $I)
    {
        $this->auth($I);
        $I->amBearerAuthenticated($this->token);
        $I->sendGet('api/post/get-all-posts');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
    }

    public function getMyPosts(ApiTester $I)
    {
        //   $this->auth($I);
        $I->amBearerAuthenticated($this->token);
        $I->sendGet('api/post/get-my-posts');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
    }

    public function createForRegistered(ApiTester $I)
    {
        // $this->auth($I);
        $I->amBearerAuthenticated($this->token);
        $I->sendPost('api/post',[
            'post' => 'post123',
            'status' => 'auth' //post visible for authorized users
        ]);
        $I->seeResponseCodeIs(201);
    }

    public function createPublicPost(ApiTester $I)
    {
        //  $this->auth($I);
        $I->amBearerAuthenticated($this->token);

        $I->sendPost('api/post',[
            'post' => 'post123'//default status is public
        ]);

        $I->seeResponseCodeIs(201);
    }

    public function createPrivatePost(ApiTester $I)
    {
        //  $this->auth($I);
        $I->amBearerAuthenticated($this->token);
        $I->sendPost('api/post',[
            'post' => 'post123',
            'status' => 'private'
        ]);
        $I->seeResponseCodeIs(201);
    }

    public function updateUnauthorized(ApiTester $I)
    {
        $I->sendPatch('api/post/1',[
            'post' => 'msgUpdated'
        ]);
        $I->seeResponseCodeIs(401);
    }

    public function update(ApiTester $I)
    {
        //$this->auth($I);
        $I->amBearerAuthenticated('mIQBro6wyJxjBtXMSAce1-sP_7VaBp6B');
        $I->sendPatch('api/post/4',[
            'post' => 'postUpdatedTTT'
        ]);
        $I->seeResponseCodeIs(200);
    }

    public function replyUnauthorized(ApiTester $I)
    {
        $I->sendPost('api/post/1/reply',[
            'reply' => 'answer'
        ]);
        $I->seeResponseCodeIs(401);
    }

    public function reply(ApiTester $I)
    {
        // $this->auth($I);
        $I->amBearerAuthenticated($this->token);
        $I->sendPost('api/post/1/reply',[
            'reply' => 'answer'
        ]);
        $I->seeResponseCodeIs(201);
    }

    public function getReplies(ApiTester $I)
    {
        // $this->auth($I);
        $I->amBearerAuthenticated($this->token);
        $I->sendGet('api/post/1/get-replies');
        $I->seeResponseCodeIs(200);
    }

    public function deleteUnauthorized(ApiTester $I)
    {
        $I->sendDelete('api/post/1');
        $I->seeResponseCodeIs(401);
    }

    public function delete(ApiTester $I)
    {
        $this->auth($I);
        $I->amBearerAuthenticated($this->token);
        $I->sendDelete('api/post/1');
        $I->seeResponseCodeIs(200);
    }
}
