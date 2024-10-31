<?php
/**
 * Radio field displayed in the plugin settings form.
 *
 * @var mixed[] $option Data of a field.
 * @package Questpass
 */

?>
<?php if ( $option['label'] ) : ?>
	<h4><?php echo esc_html( $option['label'] ); ?></h4>
<?php endif; ?>
<?php foreach ( $option['values'] as $value => $label ) : ?>
	<div class="questField">
		<input type="radio"
			name="<?php echo esc_attr( $option['key'] ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			id="<?php echo esc_attr( $option['key'] . '-' . $value ); ?>"
			class="questField__input questField__input--radio"
			<?php echo ( $value == $option['value'] ) ? 'checked' : ''; // phpcs:ignore  ?>
		>
		<label for="<?php echo esc_attr( $option['key'] . '-' . $value ); ?>"
			class="questField__label">
			<?php echo wp_kses_post( $label ); ?>
		</label>
	</div>
<?php endforeach; ?>
