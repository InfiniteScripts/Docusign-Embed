<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://infinitescripts.com/docusign_embed
 * @since      1.0.0
 *
 * @package    Docusign Embed
 * @subpackage docusign_embed/public/docusign_embed
 */


function docusign_embed_page_render(  ) {

	?>
	<form action='options.php' method='post' enctype="multipart/form-data">

		<h1>Docusign Embed Settings</h1>

		<?php


			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
      ?>
      <label class="doc-settings">Docusign Integration Key:</label><input value="<?php echo get_option('docusign_integration_key'); ?>" class="docu-input" type="text" name="docusign_integration_key"> <br>
      <label class="doc-settings">Docusign Email Login: </label><input value="<?php echo get_option('docusign_email'); ?>" class="docu-input" type="text" name="docusign_email"> <br>
      <label class="doc-settings">Docusign Account Psw: </label><input value="<?php echo get_option('docusign_password'); ?>" class="docu-input" type="text" name="docusign_password"> <br>
      <label class="doc-settings">Docusign Account ID: </label><input value="<?php echo get_option('docusign_account_id'); ?>" class="docu-input" type="text" name="docusign_account_id">
			<h2>Upload a PDF</h2>
			<input type='file' id='docusign_embed_upload_pdf' name='docusign_embed_upload_pdf'></input>
      <?php
			submit_button();
		?>

	</form>

	<h2>Current Pdf:</h2>
	<?php echo basename ( get_attached_file( get_option('docusign_pdf'), $unfiltered )); ?>


	<?php

}

function docusign_embed_settings_section_callback(  ) {
  register_setting( 'pluginPage', 'docusign_integration_key' );
}

function docusign_embed_update_settings(){
  register_setting( 'pluginPage', 'docusign_integration_key' );
}

add_shortcode( 'docusign_embed', 'docusign_shortcode');

function docusign_shortcode(){
?>
<div id="embed_area">
  <form id="gather_info" method="post" action="">
    <label class="square_up">Name:</label><input class="name" type="text" name="name"><br>
    <label class="square_up">Email:</label><input class="email" type="text" name="email"><br>    
    <label class="square_up">Designated Agent:</label><input class="agent" type="text" name="agent"><br>
    <label class="square_up">Brokerage:</label><input class="broker" type="text" name="brokerage"><br>
    <input id="submit" type="submit" value="Continue to Sign">
  </form>
</div>
<?php
}
