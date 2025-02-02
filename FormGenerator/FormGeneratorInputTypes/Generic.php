<?php
/**
 * @author selcukmart
 * 24.01.2021
 * 14:52
 */

namespace FormGenerator\FormGeneratorInputTypes;


use FormGenerator\Tools\Label;

class Generic extends AbstractInputTypes implements InputTypeInterface
{


    private
        $default_generator_arr = [
        'output' => '',
        'dont_set_id' => false,
    ];


    public function prepare(array $item): array
    {
        $this->item = $item;
        $this->item = defaults_form_generator($this->item, $this->default_generator_arr);
        $this->setLabel();
        $export_type = strtoupper($this->formGenerator->getExportType());
        $result = $this->formGenerator->render($this->item, $export_type, true);
        $this->item['output'] = $result ?: $this->item['output'];
        return [
            'input' => $this->item['output'],
            'label' => $this->item['label'],
            'input_capsule_attributes' => '',
            'label_attributes' => ''
        ];
    }


}