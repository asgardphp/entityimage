<?php
namespace Asgard\Entityimage;

class Image extends \Asgard\Entity\File {
	protected $format;
	protected $quality;

	public function format($name=null) {
		if($name === null)
			$name = $this->getName();
		$format = $this->format;
		if(!$format) {
			$format = explode('.', $name)[count(explode('.', $name))-1];
			if($format == 'jpeg')
				$format = 'jpg';
		}
		switch($format) {
			case 'gif':
				return IMAGETYPE_GIF;
			case 'jpg':
				return IMAGETYPE_JPEG;
			case 'png':
				return IMAGETYPE_PNG;
		}
		throw new \Exception('Format '.$format.' is invalid.');
	}

	public function setFormat($format) {
		$this->format = $format;
	}

	public function setQuality($quality) {
		$this->quality = $quality;
	}

	public function rename($dst, $rename=true) {
		if($this->isAt($dst))
			return;

		$format = $this->format($dst);
		$dst = preg_replace('/\.[^\.]+$/', '', $dst);

		$params = [];
		if($format == IMAGETYPE_GIF)
			$dst .= '.gif';
		elseif($format == IMAGETYPE_JPEG) {
			$params = ['jpg_quality' => $this->quality];
			$dst .= '.jpg';
		}
		elseif($format == IMAGETYPE_PNG) {
			$params = ['png_compression_level' => $this->quality];
			$dst .= '.png';
		}
		$dst = \Asgard\File\FileSystem::getNewFilename($dst);

		\Asgard\File\FileSystem::mkdir(dirname($dst));

		$imagine = new \Imagine\Gd\Imagine();
		$imagine->open($this->src)
			->save($dst, $params);

		$this->name = null;
		return $this->src = $dst;
	}
}