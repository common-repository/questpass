<?php
/**
 * Radio field displayed in the post metabox form.
 *
 * @var mixed[] $option Data of a field.
 * @package Questpass
 */

?>
<h4><?php echo esc_html( $option['label'] ); ?></h4>
<?php foreach ( $option['values'] as $value => $label ) : ?>
	<p>
		<input type="radio"
			name="<?php echo esc_attr( $option['key'] ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			id="<?php echo esc_attr( $option['key'] . '-' . $value ); ?>"
			<?php echo ( $value == $option['value'] ) ? 'checked' : ''; // phpcs:ignore  ?>
		>
		<label for="<?php echo esc_attr( $option['key'] . '-' . $value ); ?>">
			<?php echo wp_kses_post( $label ); ?>
		</label>
	</p>
<?php endforeach; ?>
