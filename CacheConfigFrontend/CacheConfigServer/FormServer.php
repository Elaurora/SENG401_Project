<?php

 class FormServer {
     private static $CACHE_CONFIG_FORM = "CacheConfigFrontend/CacheConfigGUI/CacheConfigurator.html";
     private static $MANAGE_CONFIG_FORM = "CacheConfigFrontend/CacheConfigGUI/ManageConfiguration.html";
     private static $VIEW_STATS_FORM = "CacheConfigFrontend/CacheConfigGUI/ViewStatistics.html";

     public function getForm(RequestPath $requestPath) {
         $targetForm = $requestPath->getTargetForm();

         // Get the form string...

         switch ($targetForm)
         {
             // Simple one...

             case "home":
             {
                 return file_get_contents($this::$CACHE_CONFIG_FORM);
             }

             // Send a request to the server for the local cache rules and format it
             // into a table, then return that...

             case "manageconfig":
             {
                 $formHTML = file_get_contents($this::$MANAGE_CONFIG_FORM);
                 return str_replace("{ruleTable}",
                     $this->buildHTMLTable((new RESTApiExecutor())->executeFormRequest($this->buildGetRulesRequestPath())), $formHTML);
             }

             // ...

             case "viewstats":
                 return file_get_contents($this::$VIEW_STATS_FORM);

             // Safe default if everything goes awry...

             default:
                 return file_get_contents($this::$CACHE_CONFIG_FORM);
         }
     }

     /**
      * Builds an html table from a JSON string.
      *
      * @param  $json
      *     JSON string.
      *
      * @return string
      *     HTML table equivalent of the JSON string.
      */

     private function buildHTMLTable($json) {

         // Nothing here just yet...

         return $json;
     }

     /**
      * Builds a "getallrules" RequestPath to
      * retrieve the cache rules.
      *
      * @return RequestPath
      *
      */

     private function buildGetRulesRequestPath() {
         $getRulesRequestPath = new RequestPath();
         $getRulesRequestPath->setCommandType("getallrules");
         return $getRulesRequestPath;
     }
 }