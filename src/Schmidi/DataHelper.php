<?php

namespace Schmidi;

class DataHelper {

  
  public static function withingsDataToArray($withingsData) {
  
      $output = [];
      
      foreach($withingsData->data as $element) {
	  
	  $output[] = array($element->date*1000, $element->value);
      }
      
      return $output;
  
  }
  



}