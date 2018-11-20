<?php

namespace MagedIn\Frenet\Model\Source;

class WeightType
{
    /**
     * Constants for weight
     */
    const WEIGHT_GR = 'gr';
    const WEIGHT_KG = 'kg';

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::WEIGHT_GR, 'label' => __('Grams')],
            ['value' => self::WEIGHT_KG, 'label' => __('Kilos')],
        ];
    }
}
