<?php

return [
    'accepted'       => 'Le champ :attribute doit être accepté.',
    'array'          => 'Le champ :attribute doit être un tableau.',
    'confirmed'      => 'La confirmation du champ :attribute ne correspond pas.',
    'current_password' => 'Le mot de passe actuel est incorrect.',
    'date'           => 'Le champ :attribute n\'est pas une date valide.',
    'email'          => 'Le champ :attribute doit être une adresse e-mail valide.',
    'exists'         => 'La valeur sélectionnée pour :attribute est invalide.',
    'in'             => 'La valeur du champ :attribute est invalide.',
    'lowercase'      => 'Le champ :attribute doit être en minuscules.',
    'max' => [
        'string' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
        'array'  => 'Le champ :attribute ne peut pas avoir plus de :max éléments.',
    ],
    'min' => [
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'required'       => 'Le champ :attribute est obligatoire.',
    'string'         => 'Le champ :attribute doit être une chaîne de caractères.',
    'unique'         => 'Cette valeur est déjà utilisée pour :attribute.',
    'after_or_equal' => 'Le champ :attribute doit être une date égale ou postérieure à :date.',
    'nullable'       => '',

    'custom' => [
        'email'    => ['unique' => 'Cette adresse e-mail est déjà utilisée.'],
        'password' => ['confirmed' => 'Les mots de passe ne correspondent pas.'],
        'date_fin' => ['after_or_equal' => 'La date de fin doit être égale ou postérieure à la date de début.'],
    ],

    'attributes' => [
        'nom'            => 'nom',
        'prenom'         => 'prénom',
        'name'           => 'nom',
        'email'          => 'adresse e-mail',
        'password'       => 'mot de passe',
        'date_debut'     => 'date de début',
        'date_fin'       => 'date de fin',
        'date'           => 'date de séance',
        'formation_id'   => 'formation',
        'inscription_id' => 'inscription',
        'statut'         => 'statut',
        'observation'    => 'observation',
    ],
];