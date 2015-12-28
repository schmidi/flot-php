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

            if(!array_key_exists("option", $this->options)) {
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

    public function setTimeAxis($axis = "x", $format = "%d.%m.%Y", $minTickSize = array(1, "day"))
    {

        if (!in_array('time', $this->plugins)) {
            $this->plugins[] = 'time';
        }
        switch ($axis) {
            case 'y':
                $axis = "yaxis";
                break;
            case 'x':
                // fallthru
            default:
                $axis = "xaxis";
        }

        $this->addOption($axis,
            array("mode" => "time",
                "timeformat" => $format,
                "minTickSize" => $minTickSize
            )
        );

    }

    // TODO implement

    public function setLegend() {

        return $this;
    }

    public function getAssets()
    {

        $includeTags = [];

        $includeTags[] = $this->getScriptElement($this->assetPath . "/jquery.js");
        $includeTags[] = $this->getScriptElement($this->assetPath . "/jquery.flot.js");

        foreach ($this->plugins as $plugin) {

            $includeTags[] = $this->getScriptElement($this->assetPath . "/jquery.flot." . $plugin . ".js");
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

        $html[] = implode($css, "\n");
        $html[] = "<div id=\"$this->placeholder\" class=\"css-$this->placeholder\"></div>";
        $html[] = "<script type=\"text/javascript\">" . implode($js, '\n') . "</script>";


        return implode($html, "\n");

    }


}
