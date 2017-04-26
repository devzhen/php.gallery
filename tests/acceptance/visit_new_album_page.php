<?php
$I = new AcceptanceTester($scenario);
$I->am('admin');
$I->wantToTest('Add album option');
$I->amOnPage('/admin');
$I->seeInCurrentUrl('/login');
$I->see('Login Password Sign in');
$I->wantTo('Click button Sign in');
$I->click('Sign in', 'button');
$I->seeInCurrentUrl('/admin');
$I->see('+', 'button');
$I->see('-', 'button');
$I->see('Log out', 'button');
$I->wantTo('Add album');
$I->click('+', 'button');
$I->see('Create new album');