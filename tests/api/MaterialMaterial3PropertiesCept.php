<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /material/material/3/properties');
$I->sendGET('material/material/3/properties');
$I->seeResponseEquals('{"7":{"id":7,"propertyTypeId":4,"name":"Температура плавления","sysname":null,"isSpecific":false,"propertyUnitId":5,"description":null,"propertyValues":null,"elements":null,"elementClasses":null,"elementTypes":null,"propertyUnit":null,"entity":"commonprj\\\\components\\\\core\\\\entities\\\\common\\\\property\\\\Property"}}');