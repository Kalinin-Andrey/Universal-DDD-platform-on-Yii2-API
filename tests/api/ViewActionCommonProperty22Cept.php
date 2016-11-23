<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/property/22');
$I->sendGET('common/property/22');
$I->seeResponseEquals('{"description":null,"id":22,"name":"Потребляемая энергия","sysname":null,"propertyTypeId":10,"propertyUnitId":null,"isSpecific":false,"propertyValue":null}');