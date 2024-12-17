<?php

require_once "AnalysisFile.php";

class UserAnalysisFile extends AnalysisFile{

	public function __construct(string $file_path) {
		
		if (!file_exists($file_path)) {
			throw new Exception('no such file');
		}

		$this->domain    = "user";
		$this->file_path = $file_path;
	}
}