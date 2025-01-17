<?php

class DNADecorator extends ReportDecorator {
    public function generateXML(): string {
        $originalXML = $this->report->generateXML();
        $dnaXML = "<dna>valid</dna>";
        return str_replace("</report>", $dnaXML . "</report>", $originalXML);
    }
}

?>