<?php
/**
 * @author selcukmart
 * 24.01.2021
 * 11:37
 */

namespace FormGenerator\FormGeneratorInputTypes;


use FormGenerator\Tools\Label;
use FormGenerator\Tools\Row;
use Helpers\Template;

class Select extends AbstractInputTypes implements InputTypeInterface
{


    private
        $unit_parts = [],
        $default_generator_arr = [
        'default_value' => '',
        'empty_option' => true,
        'translate_option' => false,
        'attributes' => [
            'value' => '',
            'type' => 'select',
            'class' => '',
        ],
        'option_settings' => [
            'key' => 'key',
            'label' => 'label'
        ],
        'options' => '',
        'dont_set_id' => false,
        'value_callback' => ''
    ],
        $option_settings;


    public function prepare(array $item): array
    {

        $this->item = $item;

        $this->options_data = $this->item['options'];
        $this->item = defaults_form_generator($this->item, $this->default_generator_arr);
        $this->translate_option = $this->item['translate_option'];
        $this->option_settings = $this->item['option_settings'];
        $field = $this->field = $this->item['attributes']['name'];

        $this->cleanIDInAttributesIfNecessary();

        $this->row_table = $this->formGenerator->getRowTable();

        if (isset($this->row_table[$this->field])) {
            $this->item['attributes']['value'] = $this->row_table[$this->field];

        }

        $this->setDefinedDefaultValue();
        $this->setDBDefaultValue($field);
        $this->setLabel();

        $this->value = $this->item['attributes']['value'];


        $input_dom_array = [
            'element' => 'select',
            'attributes' => $this->item['attributes'],
            'content' => $this->optionGenerate()
        ];

        $this->unit_parts = [
            'input' => $this->domExport($input_dom_array),
            'label' => $this->item['label'],
            'input_capsule_attributes' => '',
            'label_attributes' => ''
        ];


        return $this->unit_parts;
    }

    private function optionGenerate()
    {
        $row = new Row($this->formGenerator,$this->options_data);
        $row->setRow();
        $this->options_array = $row->getRow();
        $this->options = '';
        if ($this->item['empty_option']) {
            $this->options .= '<option value="">...</option>';
        }

        $key = $this->option_settings['key'];
        $this->label = $this->option_settings['label'];

        //c($this->options_array);
        if (!$this->options_array) {
            return '';
        }
        foreach ($this->options_array as $index => $option_row) {
            $attr = [
                'value' => $option_row[$key]
            ];
            if ($this->value != '' && $option_row[$key] == $this->value) {
                $attr['selected'] = 'selected';
            }

            if (isset($this->options_data['label'])) {
                $option_label = Template::smarty($option_row, $this->options_data['label']);
            } else {
                $option_label = $option_row[$this->label];
            }
            if ($this->translate_option) {
                $option_label = ___($option_label);
            }
            $arr = [
                'element' => 'option',
                'attributes' => $attr,
                'content' => $option_label
            ];
            $this->options .= $this->domExport($arr, 'option');
        }
        return $this->options;
    }


}
