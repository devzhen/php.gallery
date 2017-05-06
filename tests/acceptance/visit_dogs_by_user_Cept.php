<?php
$I = new AcceptanceTester($scenario);
$I->am('user');
$I->wantToTest('I want to visit the album, where images of dogs are stored as user');
$I->amOnPage('/albums/1');
$I->seeInCurrentUrl('/albums/1');
$I->see('Dogs');
$I->see('Back to the album list');
$I->dontSee('Logout');
$I->dontSee('Edit album');
$I->dontSee('Add to album');
$I->dontSee('Upload image:');
$I->dontSee('Select an image:');