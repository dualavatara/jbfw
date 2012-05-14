<?php
/**
 * User: dualavatara
 * Date: 4/13/12
 * Time: 9:40 PM
 */

namespace View;

class ArticleView extends BaseView {
	public $article;
	public $ctl;
	public $mtArticles;
	public $tArticles;

	public function __construct($ctl) {
		$this->ctl = $ctl;
	}

	public function show() {
		$this->start();

		$i = 1;
		echo '<div style="margin-left: 0.5em; min-width:59em;float:left;">';
		$tag = unserialize($this->article->maintag);
		if (is_array($tag)) $tag = join(',', $tag);
		$this->columnHeader($tag, 'center', 'color: #808080');
		echo '</div>';

		echo '<div>';
		foreach($this->mtArticles as $mta) {
			if ($i % 2)$style = 'style="float:left"';
			else $style = 'style="float:right"';
			echo '<div class="tagmenu" '.$style.' onClick="document.location.href=\'/article/'.$mta->id.'\';">'.$mta->name.'</div>';
			$i++;
		}
		echo '</div>';
		echo '<div style="margin-left: 0.5em; min-width:59em;float:left">';
		$this->columnHeader($this->article->name);
		echo '</div>';
		//$mainImg = $this->article->getMainImage();
		?>
	<div style="margin-left: 0.5em; min-width:50em;float:left">

		<script type="text/javascript">
			$(function () {
				// lightbox
				$('a.lightboxarticle').lightBox(
					{
						txtImage:'Фото',
						txtOf:'из'
					}
				); // Select all links with lightbox class
				/*$('a.lightbox<?php echo $id; ?>').imgPreview({
					containerID:'imgPreviewWithStyles',
					imgCSS:{
						// Limit preview size:
						height:200
					},
				});*/
			});
		</script>

		<img src="/s/<?php echo $this->article->photo_preview; ?>" style="
		border: 1px #555555 solid;
		margin-right: 1em;
		margin-bottom: 1em;
		float: left;
		"
			 alt="<?php echo $this->article->alt; ?>">

		<div style="overflow: hidden;">
		<?php
		echo $this->blockOtherImg($this->article->id, $this->article->getOtherImages(), 10000);
		?></div>
		<?php echo $this->article->content; ?>
		<p style="text-align: right;"><?php $d = new \DateTime($this->article->created); echo $d->format('d/m/Y'); ?></p>
	</div>
	<?php
		if ($this->tArticles->count()) echo "<div style='width: 60em; float:left;text-align: center'><h2 >Похожие статьи:</h2></div>";
		shuffle($this->tArticles->data);

		$this->articlesPreviewBlock($this->tArticles);
		//foreach($this->tArticles as $ta) echo $ta->name.'<br>';
		$this->end();
		return parent::show();
	}
}
