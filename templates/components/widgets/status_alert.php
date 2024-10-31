<?php
/**
 * Widget displaying the status alert on the plugin settings page.
 *
 * @var Questpass\Error\ErrorInterface|null $error Data of the detected error.
 * @package Questpass
 */

?>
<?php if ( $error !== null ) : ?>
	<?php if ( $error->is_fatal() ) : ?>
		<div class="questAlert questAlert--error">
			<?php echo esc_html( $error->get_message() ); ?>
		</div>
	<?php else : ?>
		<div class="questAlert questAlert--warning">
			<?php echo esc_html( $error->get_message() ); ?>
		</div>
	<?php endif; ?>
<?php else : ?>
	<div class="questAlert questAlert--success">
		<?php echo esc_html( __( 'The questpass display is set up correctly.', 'questpass' ) ); ?>
	</div>
<?php endif; ?>
