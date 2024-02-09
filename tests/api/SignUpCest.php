<?php


namespace Api;

use \ApiTester;
use app\fixtures\TokenFixture;
use app\fixtures\UserFixture;

class SignUpCest
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

    // tests
    public function tryToTest(ApiTester $I)
    {
    }


    public function success(ApiTester $I)
    {
        $I->sendPost('api/sign-up', [
            'login' => 'userNew',
            'password' => '1234ABC',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.status');
        $I->seeResponseJsonMatchesJsonPath('$.data.id');
        $I->seeResponseJsonMatchesJsonPath('$.data.login');
        $I->seeResponseJsonMatchesJsonPath('$.data.created_at');
    }
}
