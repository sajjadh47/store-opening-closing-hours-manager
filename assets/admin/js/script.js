if( typeof AngularObj == 'undefined' )
{
	var AngularObj = angular.module( 'sochm', [] );
}

AngularObj.controller( 'sochmController', function( $scope, $http )
{
	$scope.weekDaysTable = JSON.parse( SOCHM.weekDaysTable );
	
	$scope.addBtnText = SOCHM.addBtnText;
} );

jQuery( document ).ready( function( $ )
{
	/**
	 * Returns a random number between min (inclusive) and max (exclusive)
	 */
	function getRandomArbitrary( min, max )
	{
	    //to create an even sample distribution
    	return Math.floor( min + ( Math.random() * ( max - min + 1 ) ) );
	}

	$( document ).on( 'click', '.addNewOpeningClosing', function( event )
	{
		event.preventDefault();
		
		var clonedRow = $( this ).closest( 'tr' ).clone();

		clonedRow.find( 'td' ).first().css( 'visibility', 'hidden' );

		clonedRow.find( '.removeOpeningClosing' ).remove();
		
		clonedRow.find( '.time_dropdown option' ).removeAttr( 'selected' );
		
		clonedRow.find( '.addNewOpeningClosing' ).after( "<button style='margin-left: 15px;' class='button removeOpeningClosing' type='button'>" + SOCHM.removeBtnText + "</button>" );

		clonedRow.html( clonedRow.html().replace( /store_open_close\[\d+?\]/g, 'store_open_close[' + Date.now() + getRandomArbitrary( 5, 100 ) +']' ) );

		$( this ).closest( 'tr' ).after( clonedRow );
	} );

	$( document ).on( 'click', '.removeOpeningClosing', function( event )
	{
		event.preventDefault();

		if ( confirm( SOCHM.confirnDeleteMsg ) )
		{
			$( this ).closest( 'tr' ).remove();
		}
	} );

	$( '.removeOpeningClosing' ).each( function( index, el )
	{
		var classes = $( el ).closest( 'tr' ).attr( 'class' );

		if ( $( '.' + classes ).length < 2 )
		{
			$( '.' + classes ).find( '.removeOpeningClosing' ).remove();
		}
		else
		{
			$( '.' + classes ).first().find( '.removeOpeningClosing' ).remove();

			$( '.' + classes ).not( ':first' ).each( function( index, elm )
			{
				$( elm ).find( 'td' ).first().css( 'visibility', 'hidden' );
			} );
		}
	} );

	$( document ).on( 'click', '#sochm_hours_table #submit', function( event )
	{
		event.preventDefault();

		var self = $( this );

		$( this ).prop( 'disabled', true ).val( SOCHM.savingText );

		$.post( ajaxurl, { action: 'sochm_save_weekTable', _wpnonce: SOCHM._wpnonce, payload : jQuery( '#sochm_hours_table form' ).serialize() }, function( response )
		{
			$( self ).val( SOCHM.savedText );

			setTimeout( function()
			{
				$( self ).prop( 'disabled', false ).val( SOCHM.saveText );

			}, 1500 );

			if ( response.success === false )
			{
				alert( response.data.message );
			}
			else
			{
				location.reload();
			}
		} );

		setTimeout( function()
		{
			$( self ).prop( 'disabled', false ).val( SOCHM.saveText );

		}, 10000 );
	} );
} );