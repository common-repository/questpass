<?php
/**
 * Checkbox field displayed in the post metabox form.
 *
 * @var mixed[] $option Data of a field.
 * @package Questpass
 */

?>
<p>
	<input type="checkbox"
		name="<?php echo esc_attr( $option['key'] ); ?>"
		value="1"
		id="<?php echo esc_attr( $option['key'] ); ?>"
		<?php echo ( (string) $option['value'] === '1' ) ? 'checked' : ''; // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison ?>
	>
	<label for="<?php echo esc_attr( $option['key'] ); ?>">
		<?php echo esc_html( $option['label'] ); ?>
	</label>
</p>
