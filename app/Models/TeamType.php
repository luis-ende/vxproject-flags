<?php

namespace App\Models;

enum TeamType: string
{
    case Suscriptores = 'Suscriptores';
    case Administradores = 'Administradores';
}
