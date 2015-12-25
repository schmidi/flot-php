<?php

namespace Schmidi;

class DataSerie {


  private $label;
  
  private $data = [];
  
  private $properties = [];
  

  
  public function __construct($label = "") {
    
    $this->label = $label;
    $this->initProperties();
  
  }
  
  private function initProperties() {
  
    $this->properties['color'] = null;  
    $this->properties['lines'] = null;  
    $this->properties['bars'] = null;  
    $this->properties['points'] = null;  
    $this->properties['xaxis'] = null;  
    $this->properties['yaxis'] = null;  
    $this->properties['clickable'] = null;  
    $this->properties['hoverable'] = null;  
    $this->properties['shadowSize'] = null;  
    $this->properties['highlightColor'] = null;  
  
  }
  
  
  public function addDataArray($data) {
  
    if(is_array($data)) {
      
      $this->data = $data;
      return $this;
    
    }
    // TODO throw is not array Exception?
    return false;
    
  }
  
  
  public function addDataElement($xValue, $yValue) {
  
    if(is_scalar($xValue) && is_scalar($yValue)) {
      
      $this->data[] = array( $xValue, $yValue);
      
      return $this;
      
    }
    
    return false;
  
  }
  
  public function getProperty($property) {
    
    if(array_key_exists($property, $this->properties)) {
      return $this->properties[$property];
    }
    return false;
  
  }  
  
  
  public function setColor($color) {
    
    $this->properties['color'] = $color;
    return $this;  
  }
  
  public function withLines($properties = array("show" => true)) {
  
    if(is_array($properties)) {
    
      if(!array_key_exists("show", $properties)) {
	$properties["show"] = true;
      }
      
      $this->properties['lines'] = $properties;
      $this->properties['bars'] = null;
      $this->properties['points'] = null;
      
      return $this;
    
    }   
    return false;  
  }
  
  public function withBars($properties = array("show" => true)) {
  
    if(is_array($properties)) {
    
      if(!array_key_exists("show", $properties)) {
	$properties["show"] = true;
      }
      
      $this->properties['lines'] = null;
      $this->properties['bars'] = $properties;
      $this->properties['points'] = null;
      
      return $this;
    
    }   
    return false;  
  }
  
  public function withPoints($properties = array("show" => true)) {
  
    if(is_array($properties)) {
	
      if(!array_key_exists("show", $properties)) {
	$properties["show"] = true;
      }
      $this->properties['lines'] = null;
      $this->properties['bars'] = null;
      $this->properties['points'] = $properties;
     
      
      return $this;
    
    }   
    return false;  
  }
  
  public function setXAxis($value) {
    
    if(is_scalar($value)) {
      
      $this->properties['xaxis'] = $value;
      return $this;
    }
    return false;
  
  }
  
  public function setYAxis($value) {
    
    if(is_scalar($value)) {
      
      $this->properties['yaxis'] = $value;
      return $this;
    }
    return false;
  
  }
  
  public function toJSON() {
  
    $output = array(
	"label" => $this->label,
	"data" => $this->data,    
      );
      
    foreach($this->properties as $property => $value) {
      
      if(!is_null($value)) {
	$output[$property] = $value;
      }	
      
    }
  
    return json_encode($output);
  
  }


}