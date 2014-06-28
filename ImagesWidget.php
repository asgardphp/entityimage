<?php
namespace Asgard\Entityimage;

class ImagesWidget extends \Asgard\Form\Widget {
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
		$app = $this->field->getParent()->getApp();
		$entity = $this->field->getParent()->getEntity();
		$name = $this->field->name;		
		$optional = !$entity->property($name)->required();

		if($entity->isNew())
			return null;
		$uid = \Asgard\Common\Tools::randstr(10);
		$app['html']->codeJS("
			$(function(){
				multiple_upload('$uid', '".$app['resolver']->url_for(['Admin\Controllers\FilesController', 'add'], ['entityAlias' => $app['adminManager']->getAlias(get_class($entity)), 'id' => $entity->id, 'file' => $name])."');
			});");
		$app['html']->includeJS('bundles/admin/uploadify/jquery.uploadify.min.js');
		$app['html']->includeJS('bundles/admin/js/uploadify.php');
		$app['html']->includeCSS('bundles/admin/uploadify/uploadify.css');
		ob_start();
		?>
		<div class="block">
		
			<div class="block_head">
				<div class="bheadl"></div>
				<div class="bheadr"></div>
				
				<h2><?=$name ?></h2>
				<?php
				if(isset($options['nb']))
					echo '<span>'.$options['nb'].'</span>';
				?>
			</div>		<!-- .block_head ends -->
			
			<div class="block_content">
				<script>
				window.parentID = <?=$entity->id ?>;
				</script>
				<ul class="imglist">
					<?php
					$i=1;
					foreach($entity->$name as $file):
						$url = $file->url();
					$thumb_url = $app['request']->url->to('imagecache/admin_thumb/'.$file->relativeToWebDir());
					?>
					<li>
						<img src="<?=$thumb_url ?>" alt=""/>
						<ul>
							<li class="view"><a href="<?=$url ?>" rel="facebox"><?=__('See') ?></a></li>
							<li class="delete"><a href="<?=$app['resolver']->url_for(['Admin\Controllers\FilesController', 'deleteOne'], ['entityAlias' => $app['adminManager']->getAlias(get_class($entity)), 'id' => $entity->id, 'pos' => $i, 'file' => $name]) ?>"><?=__('Del.') ?></a></li>
						</ul>
					</li>
					<?php
					$i++;
					endforeach;
					?>
					</li>
					
				</ul>
				
				<p id="<?=$uid ?>">
					<label><?=__('Upload:') ?></label><br />
					<input type="file" id="<?=$uid ?>-filesupload" class="filesupload" /><br/>
					<span class="uploadmsg"><?=__('Maximum size 3Mb') ?></span>
					<div id="<?=$uid ?>-custom-queue"></div>
				</p>
				
			</div>		<!-- .block_content ends -->
			
			<div class="bendl"></div>
			<div class="bendr"></div>
			
		</div>		<!-- .leftcol ends -->

		<?php
		return ob_get_clean();
	}
}
