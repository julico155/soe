<?php
// app/Enums/UserType.php
namespace App\Enums;

enum UserType: string
{
    case Docente = 'docente';
    case Administrativo = 'administrativo';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}