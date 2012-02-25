<?php

namespace Form;

use Admin\Extension\Template\Template;

/**
 * Field of form, that allows to edit list of related items.
 * Consist of two lists. First contains all available item, second - related items.
 * User can move item between these two lists.
 * 
 * Input data:
 * $data = array(
 *   'name' => 'name_of_form_field',
 *   'all' => array(),
 *   'related' => array(),
 *   ['value_filed' = 'value'],
 *   ['text_filed' = 'name'],
 *   ['duplicates' = false]
 * );
 * 
 * Styling:
 * - List has 'relation-list' class hardcoded
 */
class RelationField extends Template {

	private $name;
	
	protected function show($data, $content = null) {
		$this->name = $data['name'];
		
		$all_id     = $this->generateId('all');
		$related_id = $this->generateId('related');
		$add_button = $this->generateId('add');
		$del_button = $this->generateId('del');
		$input_id   = $this->generateId('data');
		
		$value_field = isset($data['value_field']) ? $data['value_field'] : 'value';
		$text_field  = isset($data['text_field'])  ? $data['text_field']  : 'name';
		$duplicates = isset($data['duplicates']) ? (bool)$data['duplicates'] : false;
		
		if (!isset($data['all'])) {
			$data['all'] = array();
		}
		if (!isset($data['related'])) {
			$data['related'] = array();
		}
		?>
	<script type="text/javascript">
		$(document).ready(function(){
			var duplicates = <?php echo $duplicates ? 'true' : 'false'; ?>;
			var all_id     = '<?php echo $all_id; ?>';
			var related_id = '<?php echo $related_id; ?>';

			// Serialize on form submit
			$('form').submit(function() {
				$.each($('#' + related_id).find('li'), function(index, item) {
					var id = $(item).data('id');
					$('#' + '<?php echo $input_id; ?>').append($('<option value="' + id +'" selected="selected"></options>'));	
				});
			});
	
			// Sortable
			var sortableOptions = { connectWith: '.linked-list', tolerance: 'pointer' };
			$('#' + related_id).sortable(sortableOptions);
			
			if (duplicates) {
				$('#' + all_id + ' li').draggable({
				    connectToSortable: '#' + related_id,
				    helper: 'clone',
				    revert: 'invalid'
				}).droppable({
					drop: function(event, li) {
						if (li.draggable.parent().attr('id') == related_id)
							li.draggable.remove();
					}
				});
			} else {
				$('#' + all_id ).sortable(sortableOptions);
			}
			
			// Make items selectable and add handle elements
			$('#' + all_id + ', #' + related_id).mySelectable();
			
			// Add button click
			$('#' + '<?php echo $add_button; ?>').click(function(){
				var $element = $('#' + all_id + ' li.selected:visible');
				if (duplicates)
					$element = $element.clone();
				$element.appendTo('#' + related_id);
			});
			// Remove button click
			$('#' + '<?php echo $del_button; ?>').click(function(){
				var $element = $('#' + related_id + ' li.selected');
				if (duplicates) {
					$element.remove()
				} else {
					$element.appendTo('#' + all_id);
				}
			});
		});
	</script>

	<table>
		<tr>
		<td>
			<div>All Applications</div>
			<ul id="<?php echo $all_id; ?>" class="linked-list">
				<?php foreach ($data['all'] as $item): ?>
				<li data-id="<?php echo $item->$value_field; ?>"><?php echo $item->$text_field; ?></li>
				<?php endforeach; ?>
			</ul>
		</td>
		<td style="vertical-align: middle; padding: 0 10px;">
			<div id="<?php echo $add_button; ?>" class="icon icon-arrow_right"></div>
			<br />
			<br />
			<div id="<?php echo $del_button; ?>" class="icon icon-arrow_left"></div>
		</td>
		<td>
			<div>Linked Applications</div>
			<ul id="<?php echo $related_id; ?>" class="linked-list countable">
				<?php foreach ($data['related'] as $item): ?>
				<li data-id="<?php echo $item->$value_field; ?>"><?php echo $item->$text_field; ?></li>
				<?php endforeach; ?>
			</ul>
			<select id="<?php echo $input_id; ?>" name="form[<?php echo $this->name; ?>][]" multiple="multiple" style="display: none;">
			</select>
		</td>
		</tr>
	</table>
	<?php

	}
	
	private function generateId($name) {
		return sprintf('relation-%s-%s', $this->name, $name);
	}
}