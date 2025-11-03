<?php
require_once(__DIR__."/fpdf.php");

class ReporteTabla extends FPDF
{
    protected $titulo = '';
    protected $subtitulo = '';

    public function __construct($orientation='P', $unit='mm', $size='A4')
    {
        parent::__construct($orientation, $unit, $size);
    }

    public function setTitulos($titulo='', $subtitulo='')
    {
        $this->titulo = $titulo;
        $this->subtitulo = $subtitulo;
    }

    public function Header()
    {
        // Logo si existe
        $logo = __DIR__.'/../..//public/img/LogoGray.png';
        if (file_exists($logo)) {
            $this->Image($logo, 10, 8, 30);
        }
        $this->SetFont('Arial','B',12);
        $t = html_entity_decode($this->titulo ?? '', ENT_QUOTES, 'UTF-8');
        $this->Cell(0,6,utf8_decode($t),0,1,'R');
        if ($this->subtitulo) {
            $this->SetFont('Arial','',10);
            $st = html_entity_decode($this->subtitulo ?? '', ENT_QUOTES, 'UTF-8');
            $this->Cell(0,5,utf8_decode($st),0,1,'R');
        }
        $this->Ln(5);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }

    // Imprime una tabla simple
    public function Tabla(array $headers, array $rows)
    {
        // Encabezados
        $this->SetFillColor(230, 230, 230);
        $this->SetFont('Arial','B',9);
        foreach ($headers as $h) {
            $hh = html_entity_decode((string)$h, ENT_QUOTES, 'UTF-8');
            $this->Cell(40,7,utf8_decode($hh),1,0,'C',true);
        }
        $this->Ln();
        // Filas
        $this->SetFont('Arial','',9);
        foreach ($rows as $r) {
            foreach ($r as $c) {
                $val = is_string($c) ? $c : (string)$c;
                $val = html_entity_decode($val, ENT_QUOTES, 'UTF-8');
                $this->Cell(40,6,utf8_decode($val),1);
            }
            $this->Ln();
        }
    }
}
?>