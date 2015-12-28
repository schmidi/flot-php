<?php

namespace Schmidi;

class DataHelper
{


    public static function withingsDataToArray($withingsData)
    {

        $output = [];

        foreach ($withingsData->data as $element) {

            $output[] = array(
                $element->date * 1000,
                $element->value
            );
        }

        return $output;

    }

    public static function reduceDiscreteTimeValuesFrom2DArray(array $data, $valuesPerElement = 2)
    {

        $remainder = count($data) % $valuesPerElement;

        $output = [];

        $i = 0;
        $sum['x'] = 0;
        $sum['y'] = 0;

        while ($i < count($data) - $remainder) {
            $sum['x'] += $data[$i][0];
            $sum['y'] += $data[$i][1];

            if ($i % $valuesPerElement == $valuesPerElement - 1) {
                $output[] = array(
                    $sum['x'] / $valuesPerElement,
                    $sum['y'] / $valuesPerElement
                );
                $sum['x'] = 0;
                $sum['y'] = 0;
            }
            $i++;
        }
        if ($remainder > 0) {
            while ($i < count($data)) {
                $sum['x'] += $data[$i][0];
                $sum['y'] += $data[$i][1];
                $i++;
            }
            $output[] = array(
                $sum['x'] / $remainder,
                $sum['y'] / $remainder
            );
        }

        return $output;

    }


}