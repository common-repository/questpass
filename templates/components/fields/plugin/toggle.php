<?php
/**
 * Checkbox field displayed in the plugin settings form.
 *
 * @var mixed[] $option Data of a field.
 * @package Questpass
 */

?>
<div class="questField">
	<input type="checkbox"
		name="<?php echo esc_attr( $option['key'] ); ?>"
		value="1"
		id="<?php echo esc_attr( $option['key'] ); ?>"
		class="questField__input questField__input--toggle"
		<?php echo ( (string) $option['value'] === '1' ) ? 'checked' : ''; // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison ?>
	>
	<label for="<?php echo esc_attr( $option['key'] ); ?>" class="questField__label">
		<?php echo esc_html( $option['label'] ); ?>
	</label>
</div>
