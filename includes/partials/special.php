<div class="uix-field-wrapper">

	<ul class="ui-tab-nav">
		<li><a href="#ui-general" class="active"><?php esc_html_e('General','to-specials'); ?></a></li>
		<?php if(class_exists('LSX_TO_Search')) { ?>
			<li><a href="#ui-search"><?php esc_html_e('Search','to-specials'); ?></a></li>
		<?php } ?>
		<li><a href="#ui-placeholders"><?php esc_html_e('Placeholders','to-specials'); ?></a></li>
		<li><a href="#ui-archives"><?php esc_html_e('Archives','to-specials'); ?></a></li>
		<li><a href="#ui-single"><?php esc_html_e('Single','to-specials'); ?></a></li>
	</ul>

	<div id="ui-general" class="ui-tab active">
		<table class="form-table">
			<tbody>
			<?php do_action('lsx_to_framework_special_tab_content','special','general'); ?>
			</tbody>
		</table>
	</div>

	<?php if(class_exists('LSX_TO_Search')) { ?>
		<div id="ui-search" class="ui-tab">
			<table class="form-table">
				<tbody>
				<?php do_action('lsx_to_framework_special_tab_content','special','search'); ?>
				</tbody>
			</table>
		</div>
	<?php } ?>

	<div id="ui-placeholders" class="ui-tab">
		<table class="form-table">
			<tbody>
			<?php do_action('lsx_to_framework_special_tab_content','special','placeholders'); ?>
			</tbody>
		</table>
	</div>

	<div id="ui-archives" class="ui-tab">
		<table class="form-table">
			<tbody>
			<?php do_action('lsx_to_framework_special_tab_content','special','archives'); ?>
			</tbody>
		</table>
	</div>

	<div id="ui-single" class="ui-tab">
		<table class="form-table">
			<tbody>
			<?php do_action('lsx_to_framework_special_tab_content','special','single'); ?>
			</tbody>
		</table>
	</div>
	<?php do_action('lsx_to_framework_special_tab_bottom','special'); ?>
</div>
