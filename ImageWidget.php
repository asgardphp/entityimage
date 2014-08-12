<?php
namespace Asgard\Entityimage;

class ImageWidget extends \Asgard\Form\Widget {
	public function render(array $options=[]) {
		$options = $this->options+$options;
		
		$attrs = [];
		if(isset($options['attrs']))
			$attrs = $options['attrs'];

		$str = \Asgard\Form\HTMLHelper::tag('input', [
			'type'	=>	'file',
			'name'	=>	$this->name,
			'id'	=>	isset($options['id']) ? $options['id']:null,
		]+$attrs);
		$container = $this->field->getTopForm()->getContainer();
		$entity = $this->field->getTopForm()->getEntity();
		$name = $this->field->name;		
		$optional = !$entity->getDefinition()->property($name)->required();

		if($entity->isOld() && $entity->$name && $entity->$name->exists()) {
			$file = $entity->$name;
			if(!$file->src())
				return $str;
			$str .= '<p>
				<a target="_blank" href="'.$container['request']->url->to($file->srcFromWebDir()).'" rel="facebox"><img src="'.$container['imagecache']->url($file->srcFromWebDir(), 'admin_thumb').'" alt=""/></a>
			</p>';
			
			if($optional)
				$str .= '<a href="'.$container['resolver']->url_for(['Admin\Controllers\FilesController', 'delete'], ['entityAlias' => $container['adminManager']->getAlias(get_class($entity)), 'id' => $entity->id, 'file' => $name]).'">'. __('Delete').'</a><br/><br/>';
		}

		return $str;
	}
}