<?php

namespace App\Traits;

/*
 *
$visiblePropertiesArray = $models->map(function ($model) {
    return $model->getVisibleProperties();
});
 * */

trait PropertyVisibilityTrait
{
    public function getVisibleProperties()
    {
        $visibleProperties = [];

        foreach ($this->visibleProperties as $property) {
            if (isset($this->$property)) {
                $visibleProperties[$property] = $this->$property;
            }
        }

        return $visibleProperties;
    }
}
