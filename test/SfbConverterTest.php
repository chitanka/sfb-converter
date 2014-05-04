<?php

use Sfblib\SfbConverter;

class SfbConverterTest extends TestCase {

	/**
	 * @dataProvider providerForTestAddMissingCommandDelimiters
	 */
	public function testAddMissingCommandDelimiters($input, $expected) {
		$this->assertEquals($expected, rtrim(SfbConverter::addMissingCommandDelimiters($input)));
	}

	public function providerForTestAddMissingCommandDelimiters() {
		return array(
			array(
				'mytest',
				"\tmytest"
			),

			array(
				'my second test',
				"\tmy second test"
			),

			array(
				'> mytest',
				">\tmytest"
			),

			array(
				' mytest',
				"\tmytest"
			),

			array(
				"here comes empty line\n".
				"\n".
				"the end",
				"\there comes empty line\n".
				"\n".
				"\tthe end",
			),

			array(
				"P> mytest\n".
				"second line\n".
				"third line\n".
				"P$",
				"P>\tmytest\n".
				"\tsecond line\n".
				"\tthird line\n".
				"P$",
			),
		);
	}
}
