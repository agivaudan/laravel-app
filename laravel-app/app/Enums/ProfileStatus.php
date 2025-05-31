<?php

    namespace App\Enums;

    enum ProfileStatus: string
    {
        case ACTIVE = 'actif';
        case WAITING = 'en attente';
        case INACTIVE = 'inactif';
    }