<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /material/material/3/is-parent?relationGroupId=1');
$I->sendGET('material/material/3/is-parent?relationGroupId=1');
$I->seeResponseEquals('{"isParent":true}');