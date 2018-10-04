<?php
/**
 * This source file is subject to the MIT License.
 * It is also available through http://opensource.org/licenses/MIT
 *
 * @category  Frenet
 * @package   LithiumSoftware_Akhilleus
 * @author    LithiumSoftware <contato@lithiumsoftware.com.br>
 * @copyright 2015 Lithium Software
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace MagedIn\Frenet\Model\Source;

class WeightType
{
    /**
     * Constants for weight
     */
    public const WEIGHT_GR = 'gr';
    public const WEIGHT_KG = 'kg';

    /**
     * Get options for weight
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::WEIGHT_GR, 'label' => __('Gramas')),
            array('value' => self::WEIGHT_KG, 'label' => __('Kilos')),
        );
    }

}
