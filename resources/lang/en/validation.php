<?php

return [
    'required' => 'The :attribute field is required.',
    'email'    => 'The :attribute must be a valid email address.',
    'unique'   => 'The :attribute has already been taken.',
    'min'      => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'max'      => [
        'string' => 'The :attribute must not be greater than :max characters.',
    ],
    'confirmed'=> 'The :attribute confirmation does not match.',
    'numeric'  => 'The :attribute must be a number.',
    
    // أسماء الحقول باللغة الإنجليزية لتكون الرسائل دقيقة
    'attributes' => [
        'name'                  => 'name',
        'email'                 => 'email address',
        'password'              => 'password',
        'phone'                 => 'phone number',
        'blood_type'            => 'blood type',
        'hospital_name'         => 'hospital name',
        'national_id'           => 'national ID',
    ],
];