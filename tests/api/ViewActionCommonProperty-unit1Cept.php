<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/property-unit/1');
$I->sendGET('common/property-unit/1');
$I->seeResponseEquals('{"id":1,"name":"кг/м3"}');