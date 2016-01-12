<?php

namespace Schmidi;

class FlotGraph
{

    private $placeholder;

    private $options = array();

    private $data = array();

    private $plugins = array();

    private $cssStyle = array();

    private $assetPath = "";

    public function __construct()
    {

        $this->placeholder = substr(uniqid(), 0, 8);
        $this->defaultOptions();

    }

    public function setAssetPath($path)
    {

        $this->assetPath = $path;
        return $this;

    }

    private function defaultOptions()
    {

        $this->cssStyle['width'] = "300px";
        $this->cssStyle['height'] = "150px";

    }

    private function setCssStyle($property, $value)
    {

        $this->cssStyle[$property] = $value;

    }

    public function setGraphWidth($width, $unit = "px")
    {

        if (is_scalar($width) && $width > 0) {
            $this->setCssStyle('width', $width . $unit);
            return $this;
        }

        return false;
    }

    public function setGraphHeight($height, $unit = "px")
    {
        if (is_scalar($height) && $height > 0) {
            $this->setCssStyle('height', $height . $unit);
            return $this;
        }

        return false;

    }

    public function addDataSerie(FlotDataSerie $serie)
    {

        $this->data[] = $serie;

        return $this;
    }

    public function addDataSeries(array $series)
    {

        foreach ($series as $serie) {

            if ($serie instanceof FlotDataSerie) {
                $this->addDataSerie($serie);
            }
        }

        return $this;
    }

    public function getDataSeriesCount()
    {

        return count($this->data);

    }

    public function addOptions(array $options)
    {

        foreach ($options as $option => $value) {

            if (!array_key_exists('option', $this->options)) {
                $this->options[$option] = $value;
            }
        }

        return $this;

    }

    public function addOption($option, $value)
    {

        $this->options[$option] = $value;

        return $this;

    }

    public function setTimeAxis($axis = 'x', $format = '%d.%m.%Y', $minTickSize = [1, 'day'])
    {

        if (!in_array('time', $this->plugins)) {
            $this->plugins[] = 'time';
        }
        switch ($axis) {
            case 'y':
                $axis = 'yaxis';
                break;
            case 'x':
                // fallthru
            default:
                $axis = 'xaxis';
        }

        $this->options[$axis]['mode'] = 'time';
        $this->options[$axis]['timeformat'] = $format;
        $this->options[$axis]['minTickSize'] = $minTickSize;

        return $this;

    }

    // TODO implement

    public function customizeLegend($show = true, $position = 'ne', $margin = [5, 5], $backgroundColor = null,
                                    $backgroundOpacity = 0.85, $sorted = null, $labelBoxBorder = null)
    {
        $this->options['legend']['show'] = $show;
        $this->options['legend']['position'] = $position;
        $this->options['legend']['margin'] = $margin;

        if(!is_null($labelBoxBorder)) {
            $this->options['legend']['labelBoxBorderColor'] = $labelBoxBorder;
        }
        if(!is_null($backgroundColor)) {
            $this->options['legend']['backgroundColor'] = $backgroundColor;
        }
        if($backgroundOpacity != 0.85 && 0 <= $backgroundOpacity && $backgroundOpacity < 1) {
            $this->options['legend']['backgroundOpacity'] = $backgroundOpacity;
        }
        if(!is_null($sorted)) {
            $this->options['legend']['sorted'] = $sorted;
        }

        return $this;
    }

    public function getAssets($minimizedFiles = true)
    {

        $fileExtension = $minimizedFiles ? ".min.js" : ".js";

        $includeTags = [];

        $includeTags[] = $this->getScriptElement($this->assetPath . "/jquery" . $fileExtension);
        $includeTags[] = $this->getScriptElement($this->assetPath . "/jquery.flot" . $fileExtension);

        foreach ($this->plugins as $plugin) {

            $includeTags[] = $this->getScriptElement($this->assetPath . "/jquery.flot." . $plugin . $fileExtension);
        }

        return implode($includeTags, "\n");

    }

    private function getScriptElement($src)
    {

        return "<script type=\"text/javascript\" src=\"$src\"></script>";

    }


    public function drawGraph()
    {

        $flotData = "[";

        foreach ($this->data as $element) {
            $flotData .= $element->toJSON();
            $flotData .= ",";
        }

        $flotData .= "]";

        $flotOptions = json_encode($this->options);

        $css[] = "<style type=\"text/css\">";
        $css[] = ".css-$this->placeholder {";
        foreach ($this->cssStyle as $property => $value) {
            $css [] = "$property: $value; ";
        }
        $css[] = "}";
        $css[] = "</style>";

        $js[] = "$(document).ready(function(){";
        $js[] = "$.plot(\"#$this->placeholder\", $flotData, $flotOptions);";
        $js[] = "});";

        $html[] = implode("\n", $css);
        $html[] = "<div id=\"". $this->placeholder. "\" class=\"css-". $this->placeholder ."\"></div>";
        $html[] = "<script type=\"text/javascript\">" . implode("\n", $js) . "</script>";


        return implode("\n", $html);

    }


}
