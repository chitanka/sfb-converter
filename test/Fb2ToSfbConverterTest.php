<?php
class Fb2ToSfbConverterTest extends TestCase
{
	private $inputDir = 'fb2tosfb';

	public function testSectionWithParagraph()
	{
		foreach (glob(__DIR__."/$this->inputDir/*.fb2") as $fb2File) {
			$converter = $this->converter($fb2File);
			$sfbFile = str_replace('.fb2', '.sfb', $fb2File);
			$this->assertEquals(file_get_contents($sfbFile), $converter->convert());
		}
	}

	private function converter($data)
	{
		return new Sfblib_Fb2ToSfbConverter($data);
	}

}
