<?php
$I = new AcceptanceTester($scenario);
$I->am('admin');
$I->wantTo('ensure that backend works');
$I->amOnPage('/admin');
$I->see('Login Password Sign in');