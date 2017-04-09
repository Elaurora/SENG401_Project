<?php

 class FormServer {
     private static $MANAGE_CONFIG_FORM = "CacheConfigFrontend/CacheConfigGUI/ManageConfiguration.html";

     public function getForm(RequestPath $requestPath) {
         $targetForm = $requestPath->getTargetForm();

         // Get the form string...

         switch ($targetForm)
         {
             // Send a request to the server for the local cache rules and format it
             // into a table, then return that...

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
      * Builds two html tables from a json encoded string, one for rules and
      * the other for match variables. Assumes that the json encoded string is
      * provided in the form:
      *
      *     {"rules": [{...}, ..., {...}], "match_variables": [{...}, ..., {...}], ...}
      *
      * @param $jstr
      *     json encoded string containing the rules and match_variables tables.
      * @return array
      *     Array with two items: the rules table html as "rulesTable" and
      *     the vars table html as "varsTable".
      */

     private function buildHTMLTable($jstr) {

         // Decode the string into an array...

         $json = json_decode($jstr, true);

         // Get the arrays for the rules table and variables table...

         $rules     = $json["rules"];
         $matchvars = $json["variables"];

         // Open the tables and create the headers...

         $rulesTable = "<table style=\"width: 100%\"><tr><th>rule_id</th><th>local_ttl</th><th>global_ttl</th></tr>";
         $varsTable = "<table style=\"width: 100%\"><tr><th>rule_id</th><th>variable_name</th><th>variable_value</th></tr>";

         // Build the rules table...

         foreach ($rules as $rule)
         {
            $rulesTable .= "<tr>";
            $rulesTable .= "<td>" . $rule["RuleId"] . "</td>";
            $rulesTable .= "<td>" . $rule["LocalTtl"] . "</td>";
            $rulesTable .= "<td>" . $rule["GlobalTtl"] . "</td>";
            $rulesTable .= "</tr>";
         }

         // Build the variables table...

         foreach ($matchvars as $var)
         {
             $varsTable .= "<tr>";
             $varsTable .= "<td>" . $var["RuleId"] . "</td>";
             $varsTable .= "<td>" . $var["VariableName"] . "</td>";
             $varsTable .= "<td>" . $var["VariableValue"] . "</td>";
             $varsTable .= "</tr>";
         }

         // Close the tables...

         $rulesTable .= "</table>";
         $varsTable .= "</table>";

         // Put the tables into an array and return this.

         $htmlTables = array("rulesTable" => $rulesTable, "varsTable" => $varsTable);

         return $htmlTables;
     }

     /**
      * Builds a "getallrules" RequestPath to
      * retrieve the cache rules.
      *
      * @return RequestPath
      *     A request for all rules. Contains only the command type
      *     "getallrules".
      */

     private function buildGetRulesRequestPath() {
         $getRulesRequestPath = new RequestPath();
         $getRulesRequestPath->setCommandType("getallrules");
         return $getRulesRequestPath;
     }

     /**
      * Sends a request for the rules and match_variables tables and
      * returns the request result.
      *
      * @return bool|string
      *     rules and match_variables tables as a json-encoded string,
      *     if successful.
      */
     private function getTablesAsJSON() {
         return (new RESTApiExecutor())->executeFormRequest($this->buildGetRulesRequestPath());
     }
 }