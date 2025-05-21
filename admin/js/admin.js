jQuery( document ).ready( function( $ )
{
	var data = {
		weekDaysTable: JSON.parse( StoreOpeningClosingHoursManager.weekDaysTable ),
		statusOpen: StoreOpeningClosingHoursManager.statusOpen,
		statusClosed: StoreOpeningClosingHoursManager.statusClosed,
		addBtnText: StoreOpeningClosingHoursManager.addBtnText,
		removeBtnText: StoreOpeningClosingHoursManager.removeBtnText,
		today: StoreOpeningClosingHoursManager.today,
		todayText: StoreOpeningClosingHoursManager.todayText,
	};

	var template = wp.template( 'store-hours-table-template' );
    var html     = template( data );
    
    $( '#store-hours-table-body' ).html( html );

	/**
	 * Returns a random number between min (inclusive) and max (exclusive).
	 *
	 * This function generates a random integer within the specified range.
	 * It's used to create unique IDs for dynamically added form elements.
	 *
	 * @param {number} min The minimum value of the range (inclusive).
	 * @param {number} max The maximum value of the range (exclusive).
	 * @returns {number} A random integer between min and max (inclusive).
	 */
	function getRandomArbitrary( min, max )
	{
		// To create an even sample distribution
		return Math.floor( min + ( Math.random() * ( max - min + 1 ) ) );
	}

	/**
	 * Adds a new row for opening and closing hours.
	 *
	 * This event handler is triggered when the user clicks the "Add" button
	 * to add another time range for a specific day.  It clones an existing row,
	 * modifies it, and appends it to the table.
	 */
	$( document ).on( 'click', '.addNewOpeningClosing', function( event )
	{
		event.preventDefault(); // Prevent the default button behavior.
		
		var clonedRow = $( this ).closest( 'tr' ).clone(); // Clone the current table row.

		clonedRow.find( 'td' ).first().css( 'visibility', 'hidden' ); // Hide the first cell.

		clonedRow.find( '.removeOpeningClosing' ).remove(); // Remove existing remove buttons.
		
		clonedRow.find( '.time_dropdown option' ).removeAttr( 'selected' ); // Clear selected options in dropdowns.
		
		clonedRow.find( '.addNewOpeningClosing' ).after( "<button style='margin-left: 15px;' class='button removeOpeningClosing' type='button'>" + StoreOpeningClosingHoursManager.removeBtnText + "</button>" ); // Add a remove button.

		// Update the name attributes of the input elements in the cloned row to ensure unique names.
		clonedRow.html( clonedRow.html().replace( /store_open_close\[\d+?\]/g, 'store_open_close[' + Date.now() + getRandomArbitrary( 5, 100 ) +']' ) );

		$( this ).closest( 'tr' ).after( clonedRow ); // Insert the cloned row after the current row.
	} );

	/**
	 * Removes an opening and closing hours row.
	 *
	 * This event handler is triggered when the user clicks the "Remove" button
	 * to delete a time range row.  It confirms the deletion with the user
	 * before removing the row.
	 */
	$( document ).on( 'click', '.removeOpeningClosing', function( event )
	{
		event.preventDefault(); // Prevent default button behavior.

		if ( confirm( StoreOpeningClosingHoursManager.confirnDeleteMsg ) ) // Confirm with the user.
		{
			$( this ).closest( 'tr' ).remove(); // Remove the row.
		}
	} );

	/**
	 * Manages the display of the remove button.
	 *
	 * This function ensures that there is always at least one row for each day
	 * and hides the remove button for the first row of each day.
	 */
	$( '.removeOpeningClosing' ).each( function( index, el )
	{
		var classes = $( el ).closest( 'tr' ).attr( 'class' ); // Get the class name of the row.

		if ( $( '.' + classes ).length < 2 ) // If there is only one row for this day.
		{
			$( '.' + classes ).find( '.removeOpeningClosing' ).remove(); // Remove the remove button.
		}
		else // If there are multiple rows for this day.
		{
			$( '.' + classes ).first().find( '.removeOpeningClosing' ).remove(); // Remove from first row.

			$( '.' + classes ).not( ':first' ).each( function( index, elm ) //For all other rows
			{
				$( elm ).find( 'td' ).first().css( 'visibility', 'hidden' ); // Hide the first cell
			} );
		}
	} );

	/**
	 * Submits the form data via AJAX.
	 *
	 * This event handler is triggered when the user clicks the "Save" button.
	 * It serializes the form data, sends it to the server via an AJAX request,
	 * and handles the response.
	 */
	$( document ).on( 'click', '#sochm_hours_table #submit', function( event )
	{
		event.preventDefault(); // Prevent the default form submission.

		var self = $( this ); // Store the button element.

		$( this ).prop( 'disabled', true ).val( StoreOpeningClosingHoursManager.savingText ); // Disable the button and change its text.

		// Send the AJAX request.
		$.post( ajaxurl, { action: 'sochm_save_week_table', _wpnonce: StoreOpeningClosingHoursManager._wpnonce, payload : jQuery( '#sochm_hours_table form' ).serialize() }, function( response )
		{
			$( self ).val( StoreOpeningClosingHoursManager.savedText ); // Update button text.

			setTimeout( function()
			{
				$( self ).prop( 'disabled', false ).val( StoreOpeningClosingHoursManager.saveText ); // Re-enable the button and reset text after 1.5 seconds.

			}, 1500 );

			if ( response.success === false ) // If the server returned an error.
			{
				alert( response.data.message ); // Display the error message.
			}
			else // If the server returned success.
			{
				location.reload(); // Reload the page.
			}
		} );

		setTimeout( function()
		{
			$( self ).prop( 'disabled', false ).val( StoreOpeningClosingHoursManager.saveText );

		}, 10000 );
	} );
} );