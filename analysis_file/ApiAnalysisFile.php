<?php

require_once "AnalysisFile.php";

class ApiAnalysisFile extends AnalysisFile{

	public function __construct(string $file_path) {
		
		if (!file_exists($file_path)) {
			throw new Exception('no such file');
		}

		$this->domain    = "api";
		$this->file_path = $file_path;
	}
}