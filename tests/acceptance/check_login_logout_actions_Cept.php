<?php
$I = new AcceptanceTester($scenario);
$I->am('admin');
$I->wantToTest('Logout option');
$I->amOnPage('/admin');
$I->see('Login Password Sign in');
$I->wantTo('Click button Sign in');
$I->click('Sign in', 'button');
$I->see('+', 'button');
$I->see('-', 'button');
$I->see('Log out', 'button');
$I->wantTo('Log out');
$I->amOnPage('/logout');
$I->dontSee('+', 'button');
$I->dontSee('-', 'button');
$I->dontSee('Log out', 'button');