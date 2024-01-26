<?php


namespace Api;

use \ApiTester;
use app\models\Token;
use app\fixtures\TokenFixture;
use app\fixtures\UserFixture;


class AuthCest
{

    public function _before(ApiTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir().'user.php'
            ],
            'token' => [
                'class' => TokenFixture::class,
                'dataFile' => codecept_data_dir().'token.php'
            ]
        ]);
    }


    public function success(ApiTester $I)
    {
        $I->sendPOST('/api/login', [
            'login' => 'userNew',
            'password' => '1234ABC',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $I->seeResponseJsonMatchesJsonPath('$.expired');
    }
}
