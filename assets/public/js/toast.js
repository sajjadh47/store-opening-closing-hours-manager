var SOCHM_TOAST =
{
	show : function ( { message, type } )
	{
		var container_icon = jQuery( '#sochm-toast .sochm-toast-icon' );        
		
		var toast = jQuery( '#sochm-toast' )
		
		jQuery( toast ).addClass( 'sochm-toast-visible' );                
		
		jQuery( '#sochm-toast .sochm-toast-message .sochm-toast-text-2' ).html( message );
		
		var root = document.documentElement;
	
		switch ( type )
		{
			case 'success':          
				
				jQuery( toast ).addClass( 'sochm-toast-type-success' );
				
				jQuery( toast ).removeClass( 'sochm-toast-type-error' );
				
				jQuery( toast ).removeClass( 'sochm-toast-type-warning' );
 
			break;
			
			case 'error':              
				
				jQuery( toast ).addClass( 'sochm-toast-type-error' );
				
				jQuery( toast ).removeClass( 'sochm-toast-type-success' );
				
				jQuery( toast ).removeClass( 'sochm-toast-type-warning' );

			break;

			case 'warning':

				jQuery( toast ).removeClass( 'sochm-toast-type-error' );
				
				jQuery( toast ).removeClass( 'sochm-toast-type-success' );
				
				jQuery( toast ).addClass( 'sochm-toast-type-warning' );

			break;
		}    
	}
}
