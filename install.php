<?php
require_once "vendor/autoload.php";
require_once "vendor/bin/generated-conf/config.php";


$globalDBIP = '104.198.102.12';


$subscribeResults = file_get_contents($globalDBIP . '/?type=subscribe');

echo 'Got rule results: <br><pre>';
echo $subscribeResults;
echo '</pre>';

$subscribeResults = json_decode($subscribeResults);

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
echo '<br>Rules Parsed...<br>';
//iterate over each variable and add it to the rule
foreach($variables as $variable) {
	$toSave = new CacheMatchVariable();
	$toSave->setVariableName($variable['VariableName']);
	$toSave->setVariableValue($variable['VariableValue']);
	
	$rulesToSave[$variable['RuleId']]->addCacheMatchVariable($toSave);
}
echo '<br>Variables Parsed...<br>';
//iterate over each rule and save
foreach($rulesToSave as $ruleToSave) {
	$rulesToSave->save();
}
echo '<br>Rules Saved...<br>';