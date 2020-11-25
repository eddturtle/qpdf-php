# Qpdf PHP

PHP wrapper for the qpdf library.

Basic Example:

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