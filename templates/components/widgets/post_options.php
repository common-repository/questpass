<?php
/**
 * Widget displayed the settings form in the post metabox.
 *
 * @var mixed[] $options      Options of the plugin settings.
 * @var string  $submit_value A value of the submit button.
 * @var string  $nonce_key    A name of the security param.
 * @var string  $nonce_value  A value of the security param.
 * @package Questpass
 */

?>
<input type="hidden" name="<?php echo esc_attr( $submit_value ); ?>" value="1">
<input type="hidden" name="<?php echo esc_attr( $nonce_key ); ?>" value="<?php echo esc_attr( $nonce_value ); ?>">
<?php foreach ( $options as $index => $option ) : ?>
	<?php include dirname( __DIR__ ) . '/fields/post/' . $option['type'] . '.php'; ?>
<?php endforeach; ?>
