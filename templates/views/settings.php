<?php
/**
 * The admin page of the plugin settings.
 *
 * @var Questpass\Error\ErrorInterface|null $error        Data of the detected error.
 * @var mixed[]                             $groups       Groups of the plugin settings.
 * @var mixed[]                             $options      Options of the plugin settings.
 * @var string                              $submit_value The value of the submit button.
 * @var string                              $nonce_key    The name of the security param.
 * @var string                              $nonce_value  The value of the security param.
 * @var string                              $settings_url The URL of the plugin settings page.
 * @package Questpass
 */

?>
<div class="wrap">
	<hr class="wp-header-end">
	<form method="post" action="<?php echo esc_url( $settings_url ); ?>" class="questPage">
		<h1 class="questPage__headline"><?php echo esc_html( __( 'Questpass', 'questpass' ) ); ?></h1>
		<div class="questPage__inner">
			<ul class="questPage__columns">
				<li class="questPage__column questPage__column--large">
					<?php if ( isset( $_POST[ $submit_value ] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>
						<div class="questAlert questAlert--info">
							<?php echo esc_html( __( 'Changes were successfully saved!', 'questpass' ) ); ?>
						</div>
					<?php endif; ?>
					<?php require_once dirname( __DIR__ ) . '/components/widgets/status_alert.php'; ?>
				</li>
			</ul>
			<?php require_once dirname( __DIR__ ) . '/components/widgets/plugin_options.php'; ?>
		</div>
	</form>
</div>
