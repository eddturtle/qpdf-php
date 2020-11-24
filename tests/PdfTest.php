<?php

namespace EddTurtle\Qpdf\Tests;

use EddTurtle\Qpdf\Pdf;
use PHPUnit\Framework\TestCase;

class PdfTest extends TestCase
{

    public function testGetVersion()
    {
        $pdf = new Pdf();
        $version = $pdf->getVersion();

        // Test it works
        $this->assertStringContainsString('qpdf version', $version);

        // Test no errors
        $this->assertEmpty($pdf->getError());
    }

    public function testMerge()
    {
        $pdf = new Pdf();
        $pdf->addPage(__DIR__ . "/files/TestPdf.pdf");
        $pdf->addPage(__DIR__ . "/files/TestPdf2.pdf");
        $pdf->merge(__DIR__ . "/output/test-merge.pdf");
        $this->assertEmpty($pdf->getError());
    }

}