<?php

use Sfblib\Fb2ToSfbConverter;

class Fb2ToSfbConverterTest extends TestCase {
	private $inputDir = 'fb2tosfb';

	public function testSectionWithParagraph() {
		foreach (glob(__DIR__."/$this->inputDir/*.fb2") as $fb2File) {
			$converter = $this->converter($fb2File);
			$sfbFile = str_replace('.fb2', '.sfb', $fb2File);
			$sfbResult = $converter->convert();
			$this->assertEquals(file_get_contents($sfbFile), $sfbResult);
		}
	}

	private function converter($data) {
		return new Fb2ToSfbConverter($data);
	}

}
