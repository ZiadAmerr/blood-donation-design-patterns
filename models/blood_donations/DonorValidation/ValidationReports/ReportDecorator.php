<?php
abstract class ReportDecorator implements IReport {
    protected IReport $report;

    public function __construct(IReport $report) {
        $this->report = $report;
    }

    abstract public function generateXML(): string;
}

?>