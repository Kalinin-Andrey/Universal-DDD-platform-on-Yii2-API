<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/variant/2?variantTypeId=1');
$I->sendGET('common/variant/2?variantTypeId=1');
$I->seeResponseEquals('{"id":2,"elementId":1,"propertyId":2,"valueTableId":4,"valueId":1,"elementTypeId":1,"propertyValue":null,"variantTypeId":null}');