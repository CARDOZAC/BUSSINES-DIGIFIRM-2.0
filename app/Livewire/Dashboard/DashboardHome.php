<?php

namespace App\Livewire\Dashboard;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardHome extends Component
{
    private const VERSICULOS = [
        'Todo lo puedo en Cristo que me fortalece. — Fil 4:13',
        'El Señor es mi pastor, nada me faltará. — Sal 23:1',
        'Confía en el Señor de todo corazón. — Prov 3:5',
        'Todo tiene su tiempo. — Ecl 3:1',
        'Con Dios todo es posible. — Mt 19:26',
        'No temas, porque yo estoy contigo. — Is 41:10',
        'El gozo del Señor es mi fortaleza. — Neh 8:10',
        'Buscad primeramente el reino de Dios. — Mt 6:33',
        'El amor es paciente, es bondadoso. — 1 Cor 13:4',
        'Jehová es mi luz y mi salvación. — Sal 27:1',
        'En paz me acostaré y asimismo dormiré. — Sal 4:8',
        'Esfuérzate y sé valiente. — Jos 1:9',
        'La fe es la certeza de lo que se espera. — Heb 11:1',
        'El que habita al abrigo del Altísimo. — Sal 91:1',
        'Dad y se os dará. — Lc 6:38',
        'Bienaventurados los pacificadores. — Mt 5:9',
        'No os afanéis por el día de mañana. — Mt 6:34',
        'La paz de Dios sobrepasa todo entendimiento. — Fil 4:7',
        'Amarás al Señor tu Dios. — Mt 22:37',
        'El que cree en mí, tiene vida eterna. — Jn 6:47',
        'Venid a mí todos los que estáis cansados. — Mt 11:28',
        'Porque de tal manera amó Dios al mundo. — Jn 3:16',
        'El fruto del Espíritu es amor, gozo, paz. — Gál 5:22',
        'Más bienaventurado es dar que recibir. — Hch 20:35',
        'Jehová te bendiga y te guarde. — Núm 6:24',
        'En todo dad gracias. — 1 Tes 5:18',
        'No nos cansemos de hacer bien. — Gál 6:9',
        'El que persevera hasta el fin, será salvo. — Mt 24:13',
        'Mi gracia es suficiente para ti. — 2 Cor 12:9',
        'SOMOS EQUIPO, SOMOS FUERZA. — Col 3:23',
    ];

    public function getSaludoProperty(): string
    {
        $hora = (int) now()->format('G');
        $nombre = Auth::user()->name;
        $nombre = explode(' ', $nombre)[0];

        // Hasta medio día (0:00 - 11:59): Buenos días
        if ($hora < 12) {
            return "Buenos días, {$nombre} ☀️";
        }
        // Después de medio día hasta 6 PM (12:00 - 17:59): Buenas tardes
        if ($hora < 18) {
            return "Buenas tardes, {$nombre} 🌤️";
        }
        // Noche (18:00 - 23:59): Buenas noches
        return "Buenas noches, {$nombre} 🌙";
    }

    public function getVersiculoDelDiaProperty(): string
    {
        $indice = (int) now()->format('z') % count(self::VERSICULOS);

        return self::VERSICULOS[$indice];
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-home')->layout('layouts.app');
    }
}
