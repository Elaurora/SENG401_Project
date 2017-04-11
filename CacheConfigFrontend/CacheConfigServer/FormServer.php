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
      * A request for the cache hits and misses.
      * @var RequestPath
      */
     private $hitsRequest;

     /**
      * FormServer constructor.
      */
     public function __construct() {
         $this->rulesRequest = new RequestPath();
         $this->rulesRequest->setCommandType("getallrules");
         
         //TODO: probably gonna need to make a thing for hits request
         $this->hitsRequest = new RequestPath();
         $this->hitsRequest->setCommandType("gethits");
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

         // Get the form string...

         $targetForm = $requestPath->getTargetForm();

         switch ($targetForm)
         {
             // Get the the rule and variable tables, parse, build, and return.

             case "manageconfig":
             default:
             {
                 $formHTML = file_get_contents($this::$MANAGE_CONFIG_FORM);

                 $htmlTables = $this->buildHTMLTables($this->getTablesAsJSON(), $this->getHitTableAsJSON());

                 $formHTML = str_replace("{rulesTable}", $htmlTables["rulesTable"], $formHTML);
                 $formHTML = str_replace("{varsTable}", $htmlTables["varsTable"], $formHTML);
                 $formHTML = str_replace("{hitsTable}", $htmlTables["hitsTable"], $formHTML);

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
      * @param $hitsJstr
      *     JSON encoded string containing the hits table.
      * @return array
      *     Array with three items: the rules table html as "rulesTable",
      *     the vars table html as "varsTable", and the hits table as "hitsTable"
      * @throws Exception
      *     On failed request.
      */
     private function buildHTMLTables($jstr, $hitsJstr) {

         // Decode the string into an array...

         $json = json_decode($jstr, true);
         $hitsJson = json_decode($hitsJstr, true);

         // Get the arrays for the rules table and variables table...

         $rules     = $json["rules"];
         $vars      = $json["variables"];
         $status    = $json["status"];

         if ($status != "success")
            throw new Exception("Invalid database command: " . $json["errmsg"]);

         // Open the tables and create the headers...

         $rulesTable = "<table style=\"width: 100%\"><tr><th>rule_id</th><th>local_ttl</th><th>global_ttl</th></tr>";
         $varsTable = "<table style=\"width: 100%\"><tr><th>rule_id</th><th>variable_name</th><th>variable_value</th></tr>";
         $hitsTable = "<table style=\"width: 100%\"><tr><th>cache</th><th>hits</th><th>misses</th></tr>";

         // Build the rules table...

         if (count($rules) > 0)
             foreach ($rules as $rule)
                 $rulesTable .= "<tr><td>".
                     $rule["RuleId"] . "</td><td>".
                     $rule["LocalTtl"] ."</td><td>".
                     $rule["GlobalTtl"] ."</td></tr>";
         else
             $rulesTable .= "<tr><td colspan='3'>Looks like this table is empty! Did you try pressing <i>New Cache Rule</i>?</td></tr>";


         // Build the variables table...

         if (count($vars) > 0)
         {
             foreach ($vars as $var)
                 if (!empty($var["VariableName"]) && !empty($var["VariableValue"]))
                     $varsTable .= "<tr><td>".
                         $var["RuleId"] . "</td><td>".
                         $var["VariableName"] ."</td><td>".
                         $var["VariableValue"] ."</td></tr>";
         }
         else
             $varsTable .= "<tr><td colspan='3'>Looks like this table is empty! Did you try pressing <i>New Cache Rule</i>?</td></tr>";
		
		
		if (count($hitsJson) > 0) {
			foreach ($hitsJson as $ip => $hitRecord)
				if($ip != 'status')
					$hitsTable .= "<tr><td>".
						$ip . "</td><td>".
						$hitRecord["HitCount"] ."</td><td>".
						$hitRecord["MissCount"] ."</td></tr>";
		}
		else
			$hitsTable .= "<tr><td colspan='3'>Looks like this table is empty! Try making a request!</td></tr>";
						
             
         
         // Close the tables...

         $rulesTable .= "</table>";
         $varsTable .= "</table>";
         $hitsTable .= "</table>";
         // Put the tables into an array and return...

         return $htmlTables = array("rulesTable" => $rulesTable, "varsTable" => $varsTable, "hitsTable" => $hitsTable);
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
     
     /**
      * Sends a request for the hits table and
      * returns the request result.
      *
      * @return bool|string
      *     Hits table as a JSON encoded string,
      *     if successful.
      */
     private function getHitTableAsJSON(){
     	return (new RESTApiExecutor())->executeFormRequest($this->hitsRequest);
     }
 }