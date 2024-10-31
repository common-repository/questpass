<?php
/**
 * Checkbox field displayed in the post metabox form.
 *
 * @var mixed[] $option Data of a field.
 * @package Questpass
 */

?>
<h4><?php echo esc_html( $option['label'] ); ?></h4>
<p>
	<input type="text"
		name="<?php echo esc_attr( $option['key'] ); ?>"
		value="<?php echo esc_attr( $option['value'] ); ?>"
		id="<?php echo esc_attr( $option['key'] ); ?>"
	>
</p>
