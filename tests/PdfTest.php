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

        $result = $pdf->merge(__DIR__ . "/output/test-merge.pdf");
        $this->assertTrue($result);
        $this->assertEmpty($pdf->getError());
    }

    public function testSplit()
    {
        $pdf = new Pdf();

        $pdf->addPage(__DIR__ . "/files/TestPdfTwoPage.pdf");

        $result = $pdf->split(__DIR__."/output/test-split.pdf");
        $this->assertTrue($result);
        $this->assertEmpty($pdf->getError());
    }

    public function testRotate()
    {
        $pdf = new Pdf();

        $pdf->addPage(__DIR__ . "/files/TestPdfTwoPage.pdf");

        $result = $pdf->rotate(__DIR__ . "/output/rotated.pdf", "+90", "1");
        $this->assertTrue($result);
        $this->assertEmpty($pdf->getError());
    }

    public function testGetPageCount()
    {
        $pdf = new Pdf();

        $pdf->addPage(__DIR__ . "/files/TestPdfTwoPage.pdf");
        $pdf->addPage(__DIR__ . "/files/TestPdf.pdf");

        $count = $pdf->getPageCount();
        $this->assertEmpty($pdf->getError());
        $this->assertEquals(3, $count);
    }

    public function testEnsureErrorWithMultiFileRotate()
    {
        $pdf = new Pdf();
        $pdf->addPage(__DIR__ . "/files/TestPdfTwoPage.pdf");
        $pdf->addPage(__DIR__ . "/files/TestPdf.pdf");
        $this->expectExceptionMessage('Error! Currently unable to rotate when more than one PDF file is specified.');
        $pdf->rotate(__DIR__ . "/output/rotated.pdf", "+90", "1");

    }

}