<?php

namespace App\Http\Fpdf;

use Codedge\Fpdf\Fpdf\Fpdf as Fpdf;


class ReportsPayments extends Fpdf
{

    private $title;
    private $subTitle;


    var $widths;
    var $aligns;

    function setData($title, $subTitle)
    {
        $this->title        = $title;
        $this->subTitle     = $subTitle;
    }


    function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns = $a;
    }

    function Row($data, $border = 1, $fill = false, $height = 7)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = $height * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            // $this->Rect($x, $y, $w, $h);
            $this->Cell($w, $h, "", $border, 0, '', $fill);
            $this->setxy($x, $y);

            //Print the text
            $this->MultiCell($w, $height, utf8_decode($data[$i]), 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    function Header()
    {
        $this->SetFont('Courier', '', 15);
        $this->cell(190, 10, $this->title, 0, 1, 'C', false);
        $this->SetFont('Courier', '', 10);
        $this->cell(190, 8, $this->subTitle, 0, 0, 'C', false);
        $this->Ln(15);
    }

    function Footer()
    {
        $this->SetY(-20);
        $this->SetFont('Courier', 'B', 7);
        $this->cell(190, 5, utf8_decode('Sistema desarrollado por ISC.Rafael De Jesús Rebolledo Hernández'), 0, 1, 'C', false);
        $this->cell(190, 5, 'Email : rrrrebolledohdz@gmail.com', 0, 1, 'C', false);
        $this->cell(190, 5, 'Tel : 747-133-38-71', 0, 1, 'C', false);
    }
}
