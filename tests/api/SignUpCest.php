<?php


namespace Api;

use \ApiTester;

class SignUpCest
{
    public function _before(ApiTester $I)
    {
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
