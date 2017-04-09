<?php

/**
 * Receives requests for forms and finds their corresponding html.
 *
 * @authors Patrick and Natalie
 */
 class FormServer {

     /**
      * Path to the Manage Cache Configuration form.
      *
      * @var string
      */
     private static $MANAGE_CONFIG_FORM = "CacheConfigFrontend/CacheConfigGUI/ManageConfiguration.html";

     /**
      * A request for all rules. Will contain only the
      * command type "getallrules".
      *
      * @var RequestPath
      */
     private $rulesRequest;

     /**
      * FormServer constructor.
      */
     public function __construct() {
         $this->rulesRequest = new RequestPath();
         $this->rulesRequest->setCommandType("getallrules");
     }

     /**
      * Navigates to the requested form and retrieves its html.
      *
      * @param RequestPath $requestPath
      *     Path of the form to find.
      * @return bool|mixed|string
      *     The html of the requested form, if successful.
      *     Navigates to a default form otherwise.
      */
     public function getForm(RequestPath $requestPath) {
         $targetForm = $requestPath->getTargetForm();

         // Get the form string...

         switch ($targetForm)
         {
             // Get the the rule and variable tables, parse, build, and return.

             case "manageconfig":
             default:
             {
                 $formHTML = file_get_contents($this::$MANAGE_CONFIG_FORM);

                 $htmlTables = $this->buildHTMLTable($this->getTablesAsJSON());

                 $formHTML = str_replace("{rulesTable}", $htmlTables["rulesTable"], $formHTML);
                 $formHTML = str_replace("{varsTable}", $htmlTables["varsTable"], $formHTML);

                 return $formHTML;
             }
         }
     }

     /**
      * Builds two html tables from a JSON encoded string: one for rules and
      * the other for match variables. Assumes that the JSON encoded string is
      * provided in the form:
      *
      *     {"rules": [{...}, ..., {...}], "variables": [{...}, ..., {...}], ...}
      *
      * @param $jstr
      *     JSON encoded string containing the rules and variables tables.
      * @return array
      *     Array with two items: the rules table html as "rulesTable" and
      *     the vars table html as "varsTable".
      * @throws Exception
      *     On failed request.
      */
     private function buildHTMLTable($jstr) {

         // Decode the string into an array...

         $json = json_decode($jstr, true);

         // Get the arrays for the rules table and variables table...

         $rules     = $json["rules"];
         $vars      = $json["variables"];
         $status    = $json["status"];

         if ($status != "success")
            throw new Exception("Invalid database command: " . $json["errmsg"]);

         // Open the tables and create the headers...

         $rulesTable = "<table style=\"width: 100%\"><tr><th>rule_id</th><th>local_ttl</th><th>global_ttl</th></tr>";
         $varsTable = "<table style=\"width: 100%\"><tr><th>rule_id</th><th>variable_name</th><th>variable_value</th></tr>";

         // Build the rules table...

         if (count($rules) > 0)
             foreach ($rules as $rule)
                 $rulesTable .= "<tr><td>".
                     $rule["RuleId"] . "</td><td>".
                     $rule["LocalTtl"] ."</td><td>".
                     $rule["GlobalTtl"] ."</td></tr>";
         else
             $rulesTable .= "<tr><td colspan='3'>Looks like this table is empty! Did you try pressing <i>Create New Rule</i>?</td></tr>";


         // Build the variables table...

         if (count($vars) > 0)
             foreach ($vars as $var)
                 $varsTable .= "<tr><td>".
                     $var["RuleId"] . "</td><td>".
                     $var["VariableName"] ."</td><td>".
                     $var["VariableValue"] ."</td></tr>";
         else
             $varsTable .= "<tr><td colspan='3'>Looks like this table is empty! Did you try pressing <i>Create New Rule</i>?</td></tr>";

         // Close the tables...

         $rulesTable .= "</table>";
         $varsTable .= "</table>";

         // Put the tables into an array and return...

         return $htmlTables = array("rulesTable" => $rulesTable, "varsTable" => $varsTable);
     }

     /**
      * Sends a request for the rules and match_variables tables and
      * returns the request result.
      *
      * @return bool|string
      *     Rules and variables tables as a JSON encoded string,
      *     if successful.
      */
     private function getTablesAsJSON() {
         return (new RESTApiExecutor())->executeFormRequest($this->rulesRequest);
     }
 }