<?php
/**
 * A list of checkbox fields displayed in the post metabox form.
 *
 * @var mixed[] $option Data of a field.
 * @package Questpass
 */

?>
<h4><?php echo esc_html( $option['label'] ); ?></h4>
<?php foreach ( $option['values'] as $value => $label ) : ?>
	<p>
		<input type="checkbox"
			name="<?php echo esc_attr( $option['key'] ); ?>[]"
			value="<?php echo esc_attr( $value ); ?>"
			id="<?php echo esc_attr( $option['key'] . '-' . $value ); ?>"
			<?php echo ( in_array( $value, $option['value'] ) ) ? 'checked' : ''; ?>
		>
		<label for="<?php echo esc_attr( $option['key'] . '-' . $value ); ?>">
			<?php echo wp_kses_post( $label ); ?>
		</label>
	</p>
<?php endforeach; ?>
