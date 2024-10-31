<?php
/**
 * Checkbox field displayed in the plugin settings form.
 *
 * @var mixed[] $option Data of a field.
 * @package Questpass
 */

?>
<?php if ( $option['label'] ) : ?>
	<h4><?php echo esc_html( $option['label'] ); ?></h4>
<?php endif; ?>
<div class="questInput">
	<input type="text"
		name="<?php echo esc_attr( $option['key'] ); ?>"
		value="<?php echo esc_attr( $option['value'] ); ?>"
		id="<?php echo esc_attr( $option['key'] ); ?>"
		class="questInput__field"
	>
</div>
