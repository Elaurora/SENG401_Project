<?php

 class FormServer {
     const CACHE_CONFIG_FORM = "CacheConfigFrontend/CacheConfigGUI/CacheConfigurator.html";
     const MANAGE_CONFIG_FORM = "CacheConfigFrontend/CacheConfigGUI/ManageConfiguration.html";
     const VIEW_STATS_FORM = "CacheConfigFrontend/CacheConfigGUI/ViewStatistics.html";

     public function getForm($requestPath) {
         $targetForm = $requestPath->getTargetForm();

         switch ($targetForm)
         {
             case "home":
                 return file_get_contents(FormServer::CACHE_CONFIG_FORM);

             case "manageconfig":
                 return file_get_contents(FormServer::MANAGE_CONFIG_FORM);

             case "viewstats":
                 return file_get_contents(FormServer::VIEW_STATS_FORM);

             default:
                 return file_get_contents(FormServer::CACHE_CONFIG_FORM);
         }
     }
 }