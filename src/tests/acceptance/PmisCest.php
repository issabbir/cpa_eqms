<?php

class PmisCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->fillField('p_user_name', 'admin');
        $I->fillField('p_user_pass', 'cns123');
        $I->click(['class' => 'btn-primary']);
        $I->amOnPage('/dashboard');
        $I->see('Dashboard');
    }
}
