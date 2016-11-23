<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/property-unit/5');
$I->sendGET('common/property-unit/5');
$I->seeResponseEquals('{"id":5,"name":"К"}');