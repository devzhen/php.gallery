<?php
$I = new AcceptanceTester($scenario);
$I->am('user');
$I->wantTo('ensure that frontend works');
$I->amOnPage('/');
$I->see('Albums');