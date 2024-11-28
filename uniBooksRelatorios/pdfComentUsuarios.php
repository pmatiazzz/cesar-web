<?php
require __DIR__ . '/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

try {
    ob_start();
    include dirname(__FILE__) . '/contentComentUsuarios.php';
    $content = ob_get_clean();
 
    $html2pdf = new Html2Pdf('P', 'A4', 'pt');
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    $html2pdf->output('Obras.pdf');
    
} catch (Html2PdfException $e) {
    $html2pdf->clean();
    
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}