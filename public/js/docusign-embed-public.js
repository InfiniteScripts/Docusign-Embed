(function( $ ) {


	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
$(document).ready(function() {

	
	$("#gather_info").submit(function(e){
		e.preventDefault();
		$('#embed_area').fadeOut();
		
	  $.ajax({
    	url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
    	type: 'POST',
    	data:{
      		action: 'hit_docusign_embed_api',
      		name: $('.name').val(),
      		email: $('.email').val(),
      		broker: $('.broker').val(),
      		agent: $('.agent').val(),

      
    	},
    	success: function( data ){
    		$('#embed_area').html( data);
      		$('#embed_area').fadeIn();
			

    	}
  	});
	});
});

})( jQuery );
