<?php
$I = new ApiTester($scenario);
$I->wantTo('test GET /material/material/3/models');
$I->sendGET('material/material/3/models');
$I->seeResponseEquals('{"1":{"id":1,"elementId":3,"data":"{\"somedata\": \"blahblah\"}","entity":"commonprj\\\\components\\\\core\\\\entities\\\\common\\\\model\\\\Model"}}');