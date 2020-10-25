<?php

class VS7_RetailRocket_Helper_Data extends Mage_Core_Helper_Abstract
{

    private $_storeId = 1;

    private $_attributesLimit = array(
        'filtr_height',
        'height',
        'tth_width',
        'tth_material_karkas',
        'tth_color_karkas',
        'tth_material_plafona',
        'tth_color_plafon',
        'tth_mownost_total',
        'kolvo_lamp',
        'tth_mownost_1_lampi',
        'tth_type_lampa',
        'tth_specifika',
        'tth_recomended_plowad',
        'tth_max_height',
        'tth_min_height',
        'tth_tolwina_svetilnika',
        'filtr_lamp_type_svetilnik',
        'svetilnik_style',
        'svetilnik_lampa_type',
        'svetilnik_zawita',
        'svetilniki_karkas_cvet_filtr',
        'svetilniki_energo_vmesto_nakalivaniya',
        'svetilniki_crystal_quality',
        'svetilniki_plafon_cvet_filtr',
        'svet_temperatura',
        'lampi_predlagaem',
        'svetilnik_osobennosti_konstrukcii',
        'pack_dlina',
        'pack_visota',
        'pack_shirina',
        'design_room_type',
        'design_vertical_size',
        'design_width',
        'code_sonex',
        'code_metallux',
        'code_nlight',
        'filtr_for_pomeweniya',
        'flitr_kolichestvo_lamp',
        'tth_lampi_v_komplekte',
        'tth_vodozawita',
        'design_svetilnik_type',
        'design_horizont'
    );

    private $_attributesInfo;

    public function filterAndNameProductData($product)
    {
        if (empty($this->_attributesInfo)) {
            $this->_setAttributesInfo();
        }

        $params = array();
        foreach ($product->getData() as $attributeCode => $attributeValue) {
            if (in_array($attributeCode, $this->_attributesLimit)) {
                $value = null;
                if (empty($this->_attributesInfo[$attributeCode]['options'])) {
                    $value = $product->getResource()->getAttribute($attributeCode)->getFrontend()->getValue($product);
                } else {
                    foreach ($this->_attributesInfo[$attributeCode]['options'] as $option) {
                        if ($option['value'] == $attributeValue) {
                            $value = $option['label'];
                            break;
                        }
                        $value = null;
                    }
                }

                $params[] = array(
                    'name' => $this->_attributesInfo[$attributeCode]['name'],
                    'value' => $value
                );
            }
        }

        return $params;
    }

    private function _setAttributesInfo()
    {
        $attributesInfo = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setCodeFilter($this->_attributesLimit);

        foreach ($attributesInfo as $attributeInfo) {
            if ($attributeInfo->usesSource() && $attributeInfo->getSourceModel()) {
                $options = $attributeInfo->getSource()->getAllOptions(false);
            } else {
                $options = null;
            }
            $this->_attributesInfo[$attributeInfo->getAttributeCode()] = array(
                'name' => $attributeInfo->getFrontendLabel(),
                'options' => $options
            );
        }
    }
}