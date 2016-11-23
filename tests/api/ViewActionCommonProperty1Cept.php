<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/property/1');
$I->sendGET('common/property/1');
$I->seeResponseEquals('{"description":null,"id":1,"name":"Агрегатное состояние (при нормальный условиях)","sysname":null,"propertyTypeId":10,"propertyUnitId":null,"isSpecific":false,"propertyValue":null}');