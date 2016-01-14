<?php

namespace Schmidi;

class FlotDataSerie
{


    private $label;

    private $data = [];

    private $properties = [];


    public function __construct($label = '')
    {
        $this->label = $label;
    }


    public function addDataArray($data)
    {

        if (is_array($data)) {

            $this->data = $data;
            return $this;

        }
        // TODO throw is not array Exception?
        return false;
    }


    public function addDataElement($xValue, $yValue)
    {

        if (is_scalar($xValue) && is_scalar($yValue)) {

            $this->data[] = array($xValue, $yValue);

            return $this;

        }

        return false;

    }

    public function getProperty($property)
    {

        if (array_key_exists($property, $this->properties)) {
            return $this->properties[$property];
        }

        return false;
    }


    public function setColor($color)
    {

        $this->properties['color'] = $color;

        return $this;
    }

    public function withLines($lineWidth = 0, $fill = 0, $fillColor = null)
    {

        $this->properties['bars'] = null;
        $this->properties['lines']['show'] = true;

        if ($lineWidth > 0) {
            $this->properties['lines']['lineWidth'] = $lineWidth;
        }
        if ($fill > 0) {
            $this->properties['lines']['fill'] = $fill;
        }
        if (!is_null($fillColor)) {
            $this->properties['lines']['fillColor'] = $fillColor;
        }

        return $this;
    }

    public function withPoints($lineWidth = 0, $fill = 0, $fillColor = null)
    {

        $this->properties['bars'] = null;
        $this->properties['points']['show'] = true;

        if ($lineWidth > 0) {
            $this->properties['points']['lineWidth'] = $lineWidth;
        }
        if ($fill > 0) {
            $this->properties['points']['fill'] = $fill;
        }
        if (!is_null($fillColor)) {
            $this->properties['points']['fillColor'] = $fillColor;
        }

        return $this;
    }

    public function withBars($barWidth = 0, $lineWidth = 0, $fillColor = null, $align = null)
    {

        $this->properties['lines'] = null;
        $this->properties['points'] = null;
        $this->properties['bars']['show'] = true;

        if ($barWidth > 0) {
            $this->properties['bars']['barWidth'] = $barWidth;
        }
        if ($lineWidth > 0) {
            $this->properties['bars']['lineWidth'] = $lineWidth;
        }
        if(!is_null($fillColor)){
            $this->properties['bars']['fillColor'] = $fillColor;
        }
        if (!is_null($align)) {
            $this->properties['bars']['align'] = $align;
        }


        return $this;
    }


    public function setXAxis($value = 2)
    {

        if (is_scalar($value)) {

            $this->properties['xaxis'] = $value;
            return $this;
        }

        return false;
    }

    public function setYAxis($value = 2)
    {

        if (is_scalar($value)) {

            $this->properties['yaxis'] = $value;
            return $this;
        }

        return false;
    }

    public function toJSON()
    {

        $output = array(
            "label" => $this->label,
            "data" => $this->data,
        );

        foreach ($this->properties as $property => $value) {

            if (!is_null($value)) {
                $output[$property] = $value;
            }

        }

        return json_encode($output);

    }


}