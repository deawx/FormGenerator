<?php
/**
 * @author selcukmart
 * 24.01.2021
 * 11:37
 */

namespace FormGenerator\FormGeneratorInputTypes;


use Helpers\Template;

class Textarea extends AbstractInputTypes implements InputTypeInterface
{


    private
        $default_generator_arr = [
        'default_value' => '',
        'attributes' => [
            'value' => '',
            'row' => 3,
            'type' => 'textarea',
            'class' => '',
            'placeholder' => ''
        ],
        'dont_set_id' => false,
        'value_callback' => ''
    ];


    public function prepare(array $item): array
    {
        $this->item = $item;
        $this->item = defaults_form_generator($this->item, $this->default_generator_arr);
        $field = $this->field = $this->item['attributes']['name'];

        $this->cleanIDInAttributesIfNecessary();
        $row_table = $this->formGenerator->getRowTable();


        if (!empty($this->item['value_callback']) && is_callable($this->item['value_callback'])) {
            $this->item['attributes']['value'] = htmlspecialchars(call_user_func_array($this->item['value_callback'], [$row_table, $this->field]));
        } elseif (isset($row_table[$this->field])) {
            $this->item['attributes']['value'] = htmlspecialchars($row_table[$this->field]);

        }

        $this->setDefinedDefaultValue();
        $this->setDBDefaultValue($field);
        $this->setLabel();

        $this->addPlaceholderFromLabel();

        $value = $this->item['attributes']['value'];
        $this->item['attributes']['content'] = $this->item['attributes']['value'];

        unset($this->item['attributes']['value']);

        $input_dom_array = [
            'element' => 'textarea',
            'attributes' => $this->item['attributes'],
            'content' => $value
        ];
        $output = $this->domExport($input_dom_array);
        $output = Template::embed(['content' => ''], $output, [
            'use' => 'd',
            'clean_no_longer' => false
        ]);


        return [
            'input' => $output,
            'label' => $this->item['label'],
            'input_capsule_attributes' => '',
            'label_attributes' => ''
        ];
    }


}