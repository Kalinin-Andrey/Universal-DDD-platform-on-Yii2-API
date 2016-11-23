<?php 
$I = new ApiTester($scenario);
$I->wantTo('test POST common/schema-element/1/elements');
$I->sendPOST('common/schema-element/1/elements', [
'schemaElement[0][name]' => 'name',
'schemaElement[0][isSchemaElement]' => 0,
'schemaElement[0][isActive]' => 1,
'schemaElement[0][elementTypesIds][0]' => 2,
'schemaElement[0][elementTypesIds][1]' => 3
]);
$I->canSeeResponseCodeIs(201);