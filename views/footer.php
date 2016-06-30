<div class="footer">
    <div class="container">
        <p class="muted text-center blackgray add_padding">bioCADDIE is supported by the
            <a href="http://grants.nih.gov/grants/guide/rfa-files/RFA-HL-14-031.html" target="_blank" class="white hyperlink">National Institutes of Health</a>
            through the Big Data to Knowledge, Grant 1U24AI117966-01. 
            <a href="http://www.nih.gov/" target="_blank" class="white hyperlink">NIH | </a>
            <a href="http://www.ucsd.edu/" target="_blank" class="white hyperlink">UCSD | </a>
            <a href="http://healthsciences.ucsd.edu/som/medicine/divisions/dbmi/pages/default.aspx" target="_blank" class="white hyperlink">DBMI</a>
        </p>
        <p class="muted text-center blackgray add_padding">For more information about bioCADDIE, please visit<a href="https://biocaddie.org" target="_blank" class="white hyperlink"> bioCADDIE</a></p>
    </div>
</div>

<?php
/* ==== Page Specific Scripts ==== */
if (isset($scripts)) {
    foreach ($scripts as $script) {
        echo '<script type="text/javascript" src="' . $script . '"></script>';
        echo "\r\n";
    }
}
?>
</body>

</html>
