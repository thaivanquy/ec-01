<?php

return [
    'required' => 'The :attribute field is required.',
    'email' => 'The :attribute must be a valid email address.',
    'unique' => 'The :attribute already exists. Please select another :attribute.',
    'string' => 'The :attribute must be in characters',
    'max' => [
        'string' => 'Maximum :attribute length is :max characters.',
    ],
    'integer' => 'Please specify :attribute as an integer.',
    'gt' => 'Please specify a value larger than :value for :attribute.',
    'same' => 'Please specify the same value for :attribute and :other.',
    'size' => 'The :attribute file must be smaller than :size kilobytes.',
    'min' => [
        'string' => 'Please enter :attribute using at least :min characters.',
        'numeric' => 'For :attribute, specify a number greater than or equal to :min.',
    ],
    'numeric' => 'Please specify a number for :attribute.',
    'lt' => [
        'numeric' => 'For :attribute, specify a value smaller than :value.',
    ],
    'size' => [
        'numeric' => 'Please specify :size for :attribute.',
    ],
    'regex' => 'Please specify the correct format for :attribute.',
    'exists' => 'The selected :attribute is invalid.',
    'alpha_dash' => 'For :attribute, alphabets, dashes (-), and underscores (_) can be used.',
];