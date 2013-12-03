<?php
class Af_Hacker_News extends Plugin {

   function about() {
      return array(0.1,
         'Keep only 10 Hacker news items',
         'timendum',
         false,
         "https://github.com/timendum/ttrss_af_hacker_news");
   }

   function init($host) {
      // Boilerplate to register hooks.
      $host->add_hook($host::HOOK_FEED_FETCHED, $this);
   }

   function api_version() {
      return 2;
   }

   function hook_feed_fetched($feed_data, $fetch_url, $owner_uid, $feed) {
      if (strpos($fetch_url, 'news.ycombinator.com') === FALSE) {
         // Not a what-if article
         return $feed_data;
      }

      $doc = new DOMDocument();
      if (@$doc->loadXML($feed_data)) {
         $entries = $doc->getElementsByTagName('channel')->item(0)->getElementsByTagName('item');
         for ($i = $entries->length - 1; $i > 10; $i--) {
            $entry = $entries->item($i);
            $entry->parentNode->removeChild($entry);
         }
         return $doc->saveXML();
      }
      return $feed_data;
   }
}
