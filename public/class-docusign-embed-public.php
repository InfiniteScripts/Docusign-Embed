<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.infinitescripts.com/docusign-embed
 * @since      1.0.0
 *
 * @package    docusign_embed
 * @subpackage docusign_embed/public
 */

/**
 *
 * @since      1.0.0
 * @package    docusign_embed
 * @subpackage docusign_embed/public
 * @author     Kevin Greene <kevin@infinitescripts.com>
 */



class docusign_embed_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $docusign_embed    The ID of this plugin.
	 */
	private $docusign_embed;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $docusign_embed       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	private $docusign_integration_key;
	private $docusign_email;
	private $docusign_account_id;	
	private $docusign_password;
	private $returnUrl;
	private $pdf_name;

	public function __construct( $docusign_embed, $version ) {

		$this->docusign_embed = $docusign_embed;
		$this->version = $version;
		$this->docusign_integration_key = get_option('docusign_integration_key');
		$this->docusign_account_id = get_option('docusign_account_id');
		$this->docusign_email = get_option('docusign_email');
		$this->docusign_password = get_option('docusign_password');
		$this->returnUrl = 'http://www.kevin@infinitescripts.com/docusign';
		

	}

	public $signer_name;
	public $signer_email;
	public $designated_agent;
	public $brokerage;

		/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function docusign_embed_public_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in docusign_embed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The docusign_embed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->docusign_embed, plugin_dir_url( __FILE__ ) . 'css/docusign-embed-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function docusign_embed_public_enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in docusign_embed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The docusign_embed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->docusign_embed, plugin_dir_url( __FILE__ ) . 'js/docusign-embed-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->docusign_embed, 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}




	public function hit_docusign_embed_api(){

		print_r($_POST);
		$this->signer_name = $_POST['name'];
		$this->signer_email = $_POST['email'];
		$this->brokerage = $_POST['broker'];
		$this->designated_agent = $_post['agent'];

		print_r($this->signer_email);
		
		
		// construct the authentication header:
		$header = "<DocuSignCredentials><Username>" . $this->docusign_email . "</Username><Password>" . $this->docusign_password . "</Password><IntegratorKey>" . $this->docusign_integration_key . "</IntegratorKey></DocuSignCredentials>";

		/////////////////////////////////////////////////////////////////////////////////////////////////
		// STEP 1 - Login (to retrieve baseUrl and accountId)
		/////////////////////////////////////////////////////////////////////////////////////////////////
		$url = "https://demo.docusign.net/restapi/v2/login_information";
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-DocuSign-Authentication: $header"));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,false);

		$json_response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ( $status != 200 ) {
		    echo "error calling webservice, status is:" . $status;
		    exit(-1);
		}

		$response = json_decode($json_response, true);
		$accountId = $response["loginAccounts"][0]["accountId"];
		$baseUrl = $response["loginAccounts"][0]["baseUrl"];
		curl_close($curl);		

		/////////////////////////////////////////////////////////////////////////////////////////////////
		// STEP 2 - Create and envelope using one template role (called "Signer1") and one recipient
		/////////////////////////////////////////////////////////////////////////////////////////////////

		$b64Doc = base64_encode(file_get_contents(get_attached_file( get_option('docusign_pdf'))));
		$data = array (
			"accountId" => $accountId,
		    "emailSubject" => "Envelope with Embedded Signer",
		    "recipients" => [
		        "signers" => array([
		            "email" => $this->signer_email,
		            "name" => $this->signer_name,
		            "recipientId" => "1",
		            "clientUserId" =>"1234"
		        ])
		    ],
		    "documents" => array([
		        "documentId" => "1",
		        "name" => basename ( get_attached_file( get_option('docusign_pdf'), $unfiltered )),
		        "documentBase64" => $b64Doc

		    ]),
		    "status" => "sent"
		);
		

		$data_string = json_encode($data);
		
		
		$curl = curl_init($baseUrl . "/envelopes" );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);		
		curl_setopt($curl, CURLOPT_POSTFIELDS,  $data_string);		
		curl_setopt($curl, CURLOPT_HTTPHEADER, 
			array(
		    	'Content-Type: application/json',
		    	'Content-Length: ' . strlen($data_string),
		    	"X-DocuSign-Authentication: $header" )				
		);
		
		
		$json_response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ( $status != 201 ) {
		    echo "error calling webservice, status is:" . $status . "\nerror text is --> ";
		    print_r($json_response); echo "\n";
		    exit(-1);
		}

		$response = json_decode($json_response, true);
		$envelopeId = $response["envelopeId"];
		
		//embed
		$url = $baseUrl .  "/accounts/" . $accountId ."/envelopes". $envelopeId . "/views/recipient";
		$curl = curl_init($baseUrl  ."/envelopes/". $envelopeId . "/views/recipient" );
		$data = array("username" => $this->signer_name, "email" => $this->signer_email, "recipientId" => "1","clientUserId" => "1234", "authenticationMethod" => "email", "returnUrl" => "http://www.infinitescripts.com/docusign" );
		$data_string = json_encode($data);
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		    'Content-Type: application/json',
		    'Content-Length: ' . strlen($data_string),
		    "X-DocuSign-Authentication: $header" )
		);
		
		$json_response = curl_exec($curl);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ( $status != 201 ) {
		    echo "error calling webservice, status is:" . $status . "\nerror text is --> ";
		    print_r($json_response); echo "\n";
		    exit(-1);
		}
		
		$response = json_decode($json_response, true);

		echo '<iframe width="100%" height="700px" src="' . $response['url'] . '"></iframe>';
		wp_die(); // ajax call must die to avoid trailing 0 in your response
  }



	public function docusign_embed_reports(){

		/**
		* This funcion adds the menu item and register the page with Wordpress
		**/

		add_menu_page('Docusign Embed', 'Docusign Embed', 'manage_options', 'docusign_embed_reports', 'docusign_embed_page_render');
	}
		/**
		* This function registers the settings to keep the APi key
		**/


	public function docusign_embed_settings_init(  ) {


		register_setting( 'pluginPage', 'docusign_integration_key' );
		register_setting( 'pluginPage', 'docusign_email' );
		register_setting( 'pluginPage', 'docusign_password' );
		register_setting( 'pluginPage', 'docusign_account_id' );
		register_setting( 'pluginPage', 'docusign_embed_upload_pdf', 'docusign_handle_upload');
		add_settings_section(	'docusign_embed_pluginPage_section',	__( 'API Keys', 'docusign_embed' ),	'docusign_embed_settings_section_callback',	'pluginPage');

		if(isset($_FILES['docusign_embed_upload_pdf'])){
                $pdf = $_FILES['docusign_embed_upload_pdf'];
								//wp_handle_upload($pdf);
                // Use the wordpress function to upload
                // test_upload_pdf corresponds to the position in the $_FILES array
                // 0 means the content is not associated with any other posts
                $uploaded=media_handle_upload('docusign_embed_upload_pdf', 0);
                // Error checking using WP functions
                if(is_wp_error($uploaded)){
                        echo "Error uploading file: " . $uploaded->get_error_message();
                }else{
                        echo "File upload successful!";

                        add_option('docusign_pdf',  $uploaded);

						update_option('docusign_pdf',  $uploaded);

                }


        }
	}

	public function docsign_handle_upload(){
		die('o');
		$the_file = 'docusign_embed_upload_pdf';
		$data = $_FILES[$the_file . '_file'];
    if ( '' != $data['name'] )
        $upload = wp_handle_upload( $_FILES[$the_file . '_file'], array( 'test_form' => false ) );
    else
        $upload['url'] = $input[$the_file];
    return $upload['url'];

	}
}
