<?php
namespace Asgard\Entityimage\Tests\Fixtures;

class EntityWithImage extends \Asgard\Entity\Entity {
	public static function definition(\Asgard\Entity\EntityDefinition $definition) {
		$definition->properties = [
			'name',
			'image' => 'image'
		];
	}
}