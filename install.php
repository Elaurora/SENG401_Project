<?php
require_once "vendor/autoload.php";
require_once "vendor/bin/generated-conf/config.php";


$globalDBIP = '104.198.102.12';


$subscribeResults = file_get_contents('http://' . $globalDBIP . '/?type=subscribe');



$subscribeResults = json_decode($subscribeResults);

echo 'Got Rule Results...<br>';


$rules = $subscribeResults->rules;
$variables = $subscribeResults->variables;


echo 'Parsing Rules...<br>';

$rulesToSave = array();

//iterate over each rule and prepare to save it
foreach($rules as $rule) {
	$toSave = new \CacheRule();
	$toSave->setRuleId($rule->RuleId);
	$toSave->setGlobalTtl($rule->GlobalTtl);
	$toSave->setLocalTtl($rule->LocalTtl);
	$rulesToSave[$rule->RuleId] = $toSave;
}
echo 'Rules Parsed...<br>';
echo 'Parsing Variables...<br>';
//iterate over each variable and add it to the rule
foreach($variables as $variable) {
	$toSave = new CacheMatchVariable();
	$toSave->setVariableName($variable->VariableName);
	$toSave->setVariableValue($variable->VariableValue);
	
	$rulesToSave[$variable->RuleId]->addCacheMatchVariable($toSave);
}
echo 'Variables Parsed...<br>';

echo 'Deleting Old Rules...<br>';

$variables = \CacheMatchVariableQuery::create()->find();
$variables->delete();


$rules = \CacheRuleQuery::create()->find();
$rules->delete();

echo 'Rules Deleted...<br>';



echo 'Saving Rules...<br>';
//iterate over each rule and save
foreach($rulesToSave as $ruleToSave) {
	$ruleToSave->save();
}
echo 'Rules Saved...<br>';





