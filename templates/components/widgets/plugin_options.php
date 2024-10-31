<?php
/**
 * Widget displayed settings form on the plugin settings page.
 *
 * @var mixed[] $groups       Groups of the plugin settings.
 * @var mixed[] $options      Options of the plugin settings.
 * @var string  $submit_value A value of the submit button.
 * @var string  $nonce_key    A name of the security param.
 * @var string  $nonce_value  A value of the security param.
 * @package Questpass
 */

?>
<?php foreach ( $groups as $group ) : ?>
	<ul class="questPage__columns">
		<li class="questPage__column questPage__column--large">
			<div class="questPage__widget">
				<div class="questPage__widgetInner">
					<h3 class="questPage__widgetTitle">
						<?php echo esc_html( $group['label'] ); ?>
					</h3>
					<div class="questContent">
						<?php if ( $group['desc'] ) : ?>
							<div class="questPage__widgetRow">
								<p><?php echo wp_kses_post( $group['desc'] ); ?></p>
							</div>
						<?php endif; ?>
						<?php foreach ( $options as $index => $option ) : ?>
							<?php if ( $option['group_key'] === $group['key'] ) : ?>
								<div class="questPage__widgetRow">
									<?php include dirname( __DIR__ ) . '/fields/plugin/' . $option['type'] . '.php'; ?>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</li>
		<?php if ( $group['info_title'] || $group['info_content'] ) : ?>
			<li class="questPage__column questPage__column--small">
				<div class="questPage__widget">
					<div class="questPage__widgetInner">
						<?php if ( $group['info_title'] ) : ?>
							<h3 class="questPage__widgetTitle questPage__widgetTitle--second">
								<?php echo esc_html( $group['info_title'] ); ?>
							</h3>
						<?php endif; ?>
						<?php if ( $group['info_content'] ) : ?>
							<div class="questContent">
								<?php foreach ( $group['info_content'] as $line ) : ?>
									<p><?php echo wp_kses_post( $line ); ?></p>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</li>
		<?php endif; ?>
	</ul>
<?php endforeach; ?>

<ul class="questPage__columns">
	<li class="questPage__column">
		<div class="questPage__widget">
			<input type="hidden" name="<?php echo esc_attr( $nonce_key ); ?>"
				value="<?php echo esc_attr( $nonce_value ); ?>">
			<button type="submit" name="<?php echo esc_attr( $submit_value ); ?>"
				class="questButton questButton--green">
				<?php echo esc_html( __( 'Save changes', 'questpass' ) ); ?>
			</button>
		</div>
	</li>
</ul>
