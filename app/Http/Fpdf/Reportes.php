<?php

namespace App\Http\Fpdf;

use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;


class Reportes extends Fpdf
{
    function Header()
    {
        $this->SetFont('Courier', '', 5);
        $this->cell(90, 5, 'ENTREGA', 'T', 0, 'C', false);
    }

    function Footer()
    {
    }
}
