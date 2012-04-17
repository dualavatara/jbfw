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

	public function __construct($ctl) {
		$this->ctl = $ctl;
	}

	public function show() {
		$this->start();
		$this->columnHeader($this->article->name);
		//$mainImg = $this->article->getMainImage();
		?>
	<div style="margin-left: 0.5em">
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
		<a href="/s/<?php echo $this->article->photo; ?>"
		   class="lightboxarticle">
		<img src="/s/<?php echo $this->article->photo_preview; ?>" style="
		border: 1px #555555 solid;
		margin-right: 1em;
		margin-bottom: 1em;
		float: left;
		">
			</a>
		<?php echo $this->article->content; ?>
		<p style="text-align: right;"><?php $d = new \DateTime($this->article->created); echo $d->format('d/m/Y'); ?></p>
	</div>
	<?php
		$this->end();
		return parent::show();
	}
}