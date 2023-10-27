<?php
/*
 * Checking phone and email duplicates for the business process designer
 * Variables must be added to the business process parameters:
 * Incoming_request
 * Outgoing_response
 */
$rootActivity = $this->GetRootActivity();
$value = $rootActivity->GetVariable("Incoming_request");

if(!strpos($value, '@')) {
    $q = substr($value, 0, 1);
    if (in_array($q, ['8', '7', '+'])) {
        $phoneNumber = preg_replace('/\s|\+|-|\(|\)/', '', $value);
        if (is_numeric($phoneNumber)) {
            $value = substr($value, -10);
        }
    }
}

\CModule::includeModule("crm");
$res = \CCrmFieldMulti::GetList(['ID'=>'asc'],['ENTITY_ID' =>'CONTACT','%VALUE' => $value]);
while($arEl = $res->fetch()){
    $outgoing_response = $arEl['ELEMENT_ID'];
    break;
};

$rootActivity->SetVariable("Outgoing_response",$outgoing_response);
