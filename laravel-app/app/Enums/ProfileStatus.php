<?php

    namespace App\Enums;

    // Quick enum for the status of the different profiles
    enum ProfileStatus: string
    {
        case ACTIVE     = 'actif';
        case WAITING    = 'en attente';
        case INACTIVE   = 'inactif';
    }