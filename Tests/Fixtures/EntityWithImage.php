<?php
namespace Asgard\Entityimage\Tests\Fixtures;

class EntityWithImage extends \Asgard\Entity\Entity {
	public static function definition(\Asgard\Entity\Definition $definition) {
		$definition->properties = [
			'name',
			'image' => 'image'
		];
	}
}