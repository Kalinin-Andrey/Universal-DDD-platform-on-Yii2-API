<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/property-unit');
$I->sendGET('common/property-unit');
$I->seeResponseEquals('{"1":{"id":1,"name":"кг/м3"},"2":{"id":2,"name":"См/м"},"3":{"id":3,"name":"Вт/(м*К)"},"4":{"id":4,"name":"Дж/(кг*К)"},"5":{"id":5,"name":"К"}}');