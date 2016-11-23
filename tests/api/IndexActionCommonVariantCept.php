<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /common/variant?variantTypeId=1');
$I->sendGET('common/variant?variantTypeId=1');
$I->seeResponseEquals('{"variantsByTypeId":{"1":{"1":{"id":1,"elementId":3,"elementTypeId":1,"variantTypeId":1,"propertyId":2,"valueTableId":4,"valueId":1,"propertyValue":null,"element":null,"elementType":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\common\\\\propertyVariant\\\\PropertyVariant"},"2":{"id":2,"elementId":1,"elementTypeId":1,"variantTypeId":1,"propertyId":2,"valueTableId":4,"valueId":1,"propertyValue":null,"element":null,"elementType":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\common\\\\propertyVariant\\\\PropertyVariant"}}}}');