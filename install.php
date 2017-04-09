<?php
require_once "vendor/autoload.php";
require_once "vendor/bin/generated-conf/config.php";


$globalDBIP = '104.198.102.12';


$subscribeResults = file_get_contents('http://' . $globalDBIP . '/?type=subscribe');



$subscribeResults = json_decode($subscribeResults);

echo 'Got rule results: <br><pre>';
echo print_r($subscribeResults);
echo '</pre>';

$rules = $subscribeResults->rules;
$variables = $subscribeResults->variables;

echo 'Got rules: <br><pre>';
echo print_r($rules);
echo '</pre>';

echo 'Got variables: <br><pre>';
echo print_r($variables);
echo '</pre>';

echo '<br>Parsing Rules...<br>';

$rulesToSave = array();

//iterate over each rule and prepare to save it
foreach($rules as $rule) {
	$toSave = new \CacheRule();
	$toSave->setRuleId($rule->RuleId);
	$toSave->setGlobalTtl($rule->GlobalTtl);
	$toSave->setLocalTtl($rule->LocalTtl);
	$rulesToSave[$rule->RuleId] = $toSave;
}
echo '<br>Rules Parsed...<br>';
echo '<br>Parsing Variables...<br>';
//iterate over each variable and add it to the rule
foreach($variables as $variable) {
	$toSave = new CacheMatchVariable();
	$toSave->setVariableName($variable->VariableName);
	$toSave->setVariableValue($variable->VariableValue);
	
	$rulesToSave[$variable->RuleId]->addCacheMatchVariable($toSave);
}
echo '<br>Variables Parsed...<br>';
echo '<br>Saving Rules...<br>';
//iterate over each rule and save
foreach($rulesToSave as $ruleToSave) {
	$rulesToSave->save();
}
echo '<br>Rules Saved...<br>';