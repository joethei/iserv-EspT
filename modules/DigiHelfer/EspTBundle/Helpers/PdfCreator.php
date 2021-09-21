<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Helpers;

use IServ\CoreBundle\Service\Logo;
use TCPDF;

class PdfCreator extends TCPDF {

    private $customHeaderText = "";
    private $customFooterText = "";
    private $logoSize = 30;

    /**
     * @param string $customHeaderText
     */
    public function setCustomHeaderText(string $customHeaderText): void {
        $this->customHeaderText = $customHeaderText;
    }

    /**
     * @param string $customFooterText
     */
    public function setCustomFooterText(string $customFooterText) {
        $this->customFooterText = $customFooterText;
    }

    /**
     * @param int $logoSize
     */
    public function setLogoSize(int $logoSize): void {
        $this->logoSize = $logoSize;
    }


    public function Table(array $header, array $data, array $width = array(70, 100)) {
        // Colors, line width and bold font
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header

        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($width[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();

        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor();
        $this->SetFont('');
        // Data
        $fill = 0;
        foreach($data as $row) {
            for($i = 0; $i < count($width); $i++) {
                $this->Cell($width[$i], 6, $row[$i], 'LR', 0, 'L', $fill);
            }
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($width), 0, '', 'T');
    }

    //Page header
    public function Header() {
        $image_file = Logo::PATH . 'logo.png';
        $this->Image($image_file, 10, 10, $this->logoSize, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->Ln(0);
        $this->SetFont('helvetica', 'B', 20);
        $this->Cell(0, 15, $this->title, 0, false, 'C', 0, 0, 0, false, 'M', 'M');
        $this->Ln();
        $this->setFont('', '', 10);
        $this->Cell(0, 10, $this->customHeaderText, 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, $this->customFooterText, 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
