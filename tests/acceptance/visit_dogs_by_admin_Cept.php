<?php
$I = new AcceptanceTester($scenario);
$I->am('admin');
$I->wantToTest('I want to visit the album, where images of dogs are stored as admin');
$I->amOnPage('/admin/albums/1');
$I->seeInCurrentUrl('/login');
$I->see('Login Password Sign in');
$I->wantTo('Click button Sign in');
$I->click('Sign in', 'button');
$I->seeInCurrentUrl('/admin/albums/1');
$I->see('Dogs');
$I->see('Back to the album list');
$I->see('Edit album');
$I->see('Add to album');
$I->see('Upload image:');
$I->see('Select an image:');
$I->wantTo('Log out');
$I->amOnPage('/logout');