<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/variant/1?variantTypeId=1');
$I->sendGET('common/variant/1?variantTypeId=1');
$I->seeResponseEquals('{"id":1,"elementId":3,"propertyId":2,"valueTableId":4,"valueId":1,"elementTypeId":1,"propertyValue":null,"variantTypeId":null}');