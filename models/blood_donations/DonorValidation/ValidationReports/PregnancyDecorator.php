<?php

class PregnancyDecorator extends ReportDecorator {
    public function generateXML(): string {
        $originalXML = $this->report->generateXML();
        $dnaXML = "<Pregnancy>valid</Pregnancy>";
        return str_replace("</report>", $dnaXML . "</report>", $originalXML);
    }
}

?>