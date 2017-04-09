<?php
require_once "vendor/autoload.php";
require_once "vendor/bin/generated-conf/config.php";


$globalDBIP = '104.198.102.12';


$subscribeResults = file_get_contents($globalDBIP . '/?type=subscribe');

$rules = $subscribeResults['rules'];
$variables = $subscribeResults['variables'];

$rulesToSave = array();

//iterate over each rule and prepare to save it
foreach($rules as $rule) {
	$toSave = new \CacheRule();
	$toSave->setRuleId($rule['RuleId']);
	$toSave->setGlobalTtl($rule['GlobalTtl']);
	$toSave->setLocalTtl($rule['LocalTtl']);
	$rulesToSave[$rule['RuleId']] = $toSave;
}

//iterate over each variable and add it to the rule
foreach($variables as $variable) {
	$toSave = new CacheMatchVariable();
	$toSave->setVariableName($variable['VariableName']);
	$toSave->setVariableValue($variable['VariableValue']);
	
	$rulesToSave[$variable['RuleId']]->addCacheMatchVariable($toSave);
}

//iterate over each rule and save
foreach($rulesToSave as $ruleToSave) {
	$rulesToSave->save();
}