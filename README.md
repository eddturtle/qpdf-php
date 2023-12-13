# Qpdf PHP

PHP wrapper for the qpdf library.

Currently requires PHP 7.4+ and the `qpfd` library installed (`sudo apt install qpdf`).

Basic Examples:

### Merge
```php
$qpdf = new \EddTurtle\Qpdf\Pdf();

$qpdf->addPage(__DIR__ . "/files/TestPdf.pdf");
$qpdf->addPage(__DIR__ . "/files/TestPdf2.pdf");

$result = $qpdf->merge(__DIR__ . "/output/test-merge.pdf");

if (!$result || !empty($qpdf->getError())) {
    var_dump($qpdf->getError());
} else {
    var_dump('success');
}
```

### Split a multi page PDF file
```php
$qpdf = new \EddTurtle\Qpdf\Pdf();
$qpdf->addPage(__DIR__ . "/files/TestPdfTwoPage.pdf");
$qpdf->split(__DIR__."/output/test-split.pdf");

if (!$result || !empty($qpdf->getError())) {
    var_dump($qpdf->getError());
} else {
    var_dump('success');
}
// Results in "/output/" containing 'test-split-1.pdf' and 'test-split-2.pdf'
```

### Rotate a PDF file
```php
$qpdf = new \EddTurtle\Qpdf\Pdf();
$pdf->addPage(__DIR__ . "/files/TestPdfTwoPage.pdf");
$result = $pdf->rotate(__DIR__ . "/output/rotated.pdf", "+90", "1");

if (!$result || !empty($qpdf->getError())) {
    var_dump($qpdf->getError());
} else {
    var_dump('success');
}
// Results in "/output/rotated.pdf" having its first page rotated 90&deg; clockwise
```


### Count PDF pages
```php
$qpdf = new \EddTurtle\Qpdf\Pdf();
$pdf->addPage(__DIR__ . "/files/TestPdfTwoPage.pdf");
$pdf->addPage(__DIR__ . "/files/TestPdf.pdf");
$count = $pdf->getPageCount();
// Results in $count containing a value of 3
```