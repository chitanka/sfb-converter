<?php

class Fb2ToSfbConverter
{
	const EOL = "\n";

	private $file;

	public function __construct($file)
	{
		$this->file = $file;
	}

	public function convert()
	{
		$fb2 = new SimpleXMLElement($this->file, null, true);
		$sfb = '';
		$sfb .= $this->convertMainTitle($fb2->description);
		$sfb .= $this->line();
		$sfb .= $this->convertImage($fb2->body->image);
		$sfb .= $this->convertEpigraphs($fb2->body->epigraph);
		$sfb .= $this->convertSections($fb2->body->section);

		return $sfb;
	}

	private function convertMainTitle(SimpleXMLElement $description)
	{
		$sfb = '';
		$titleInfo = $description->{'title-info'};
		$sfb .= $this->line($this->convertMainAuthor($titleInfo->author), '|');
		$sfb .= $this->line($titleInfo->{'book-title'}, '|');
		return $sfb;
	}

	private function convertMainAuthor(SimpleXMLElement $author)
	{
		return implode(' ', array(
			$author->{'first-name'},
			//$author->{'middle-name'},
			$author->{'last-name'}
		));
	}

	private function convertImage(SimpleXMLElement $image)
	{
		$sfb = '';
		if ($image) {
			$sfb = $this->line(sprintf('{img:%s}', ltrim($image->attributes('l', true)->href, '#')));
		}
		return $sfb;
	}

	private function convertEpigraphs(SimpleXMLElement $epigraphs)
	{
		$sfb = '';
		if ($epigraphs) {
			foreach ($epigraphs as $epigraph) {
				$sfb .= $this->convertEpigraph($epigraph);
			}
		}
		return $sfb;
	}

	private function convertEpigraph(SimpleXMLElement $epigraph)
	{
		$sfb = '';
		$sfb .= $epigraph->asXML();
		return $sfb;
	}

	private function convertSections(SimpleXMLElement $sections)
	{
		$sfb = '';
		if ($sections) {
			$sfb .= $this->line();
			foreach ($sections as $section) {
				$sfb .= $this->convertSection($section);
			}
		}
		return $sfb;
	}

	private function convertSection(SimpleXMLElement $section, $level = 1)
	{
		$sfb = '';
		$sfb .= $this->convertTitle($section->title, $level);
		$sfb .= $this->convertEpigraphs($section->epigraph);
		$sfb .= $this->convertImage($section->image);
		if ($section->section) {
			$sfb .= $this->convertSections($section->section);
		} else {
			foreach ($section->children() as $elm) {
				switch ($elm->getName()) {
					case 'p':
						$sfb .= $this->convertParagraph($elm); break;
					case 'image':
						$sfb .= $this->convertImage($elm); break;
					case 'poem':
						$sfb .= $this->convertPoem($elm); break;
					case 'subtitle':
						$sfb .= $this->convertSubtitle($elm); break;
					case 'cite':
						$sfb .= $this->convertCite($elm); break;
					case 'empty-line':
						$sfb .= $this->line(); break;
					case 'table':
						$sfb .= $this->convertTable($elm); break;
				}
			}
		}
		return $sfb;
	}

	private $_titleMarkers = array(
		1 => '>',
		2 => '>>',
		3 => '>>>',
		4 => '>>>>',
		5 => '>>>>>',
	);
	private function convertTitle(SimpleXMLElement $title, $level = 1)
	{
		$sfb = '';
		if ($title) {
			$sfb .= $this->line();
			foreach ($title->p as $paragraph) {
				$sfb .= $this->convertParagraph($paragraph, $this->_titleMarkers[$level]);
			}
			$sfb .= $this->line();
		}
		return $sfb;
	}

	private function convertParagraph(SimpleXMLElement $paragraph, $command = '')
	{
		$content = $this->removeElement($paragraph->asXML(), 'p');
		$content = $this->convertInlineElements($content);
		return $this->line($content, $command);
	}

	private function convertPoem(SimpleXMLElement $poem)
	{
		$sfb = '';
		$sfb .= $this->command('P>');
		$currentStanzaCount = 0;
		foreach ($poem->children() as $elm) {
			switch ($elm->getName()) {
				case 'title':
					$sfb .= $this->convertPoemTitle($elm); break;
				case 'epigraph':
					$sfb .= $this->convertEpigraphs($elm); break;
				case 'stanza':
					if ($currentStanzaCount++ > 0) {
						$sfb .= $this->line();
					}
					$sfb .= $this->convertStanza($elm); break;
				case 'text-author':
					$sfb .= $this->convertAuthor($elm); break;
				case 'date':
					$sfb .= $this->convertDate($elm); break;
				case 'empty-line':
					$sfb .= $this->line(); break;
			}
		}
		$sfb .= $this->command('P$');
		return $sfb;
	}

	private function convertSubtitle(SimpleXMLElement $subtitle)
	{
		$sfb = '';
		$sfb .= $this->line($this->convertInlineElements($subtitle->asXML()), '#');
		return $sfb;
	}

	private function convertCite(SimpleXMLElement $cite)
	{
		$sfb = '';
		$sfb .= $cite->asXML();
		return $sfb;
	}

	// TODO
	private function convertTable(SimpleXMLElement $table)
	{
		$sfb = '';
		$sfb .= $table->asXML();
		return $sfb;
	}

	private function convertStanza(SimpleXMLElement $stanza)
	{
		$sfb = '';
		foreach ($stanza->children() as $elm) {
			switch ($elm->getName()) {
				case 'title':
					$sfb .= $this->convertSubtitle($elm); break;
				case 'subtitle':
					$sfb .= $this->convertSubtitle($elm); break;
				case 'v':
					$sfb .= $this->convertVerse($elm); break;
			}
		}
		return $sfb;
	}

	private function convertVerse(SimpleXMLElement $verse)
	{
		$content = $this->removeElement($verse->asXML(), 'v');
		$content = $this->convertInlineElements($content);
		return $this->line($content);
	}

	// TODO - a, style, image
	private function convertInlineElements($xml)
	{
		$sfb = strtr($xml, array(
			'<emphasis>' => '{e}', '</emphasis>' => '{/e}',
			'<strong>' => '{s}', '</strong>' => '{/s}',
			'<sup>' => '{sup}', '</sup>' => '{/sup}',
			'<sub>' => '{sub}', '</sub>' => '{/sub}',
			'<code>' => '{pre}', '</code>' => '{/pre}',
			'<strikethrough>' => '{del}', '</strikethrough>' => '{/del}',
		));
		$reStart = '(?<=[\s([„«>])';
		$reEnd = '(?![\w\d])';
		$sfb = ' '.$sfb;
		$sfb = preg_replace("|$reStart{e}(.+){/e}$reEnd|U", '_$1_', $sfb);
		$sfb = preg_replace("|$reStart{s}(.+){/s}$reEnd|U", '__$1__', $sfb);
		$sfb = ltrim($sfb, ' ');
		return $sfb;
	}

	private function line($content = '', $command = '')
	{
		if (empty($content) && empty($command)) {
			return self::EOL;
		}
		return $command . "\t" . $content . self::EOL;
	}

	private function command($command)
	{
		return $this->line('', $command);
	}

	private function removeElement($xml, $tag)
	{
		return strtr($xml, array("<$tag>" => '', "</$tag>" => ''));
	}
}
