jQuery( document).ready( function( $ )
{
	var calculate_countdown = function( $target )
	{
		if ( typeof SOCHM.remaining_time_to_close != 'undefined' )
		{
			var remaining_time = SOCHM.remaining_time_to_close;
		}

		if ( typeof SOCHM.remaining_time_to_open != 'undefined' )
		{
			var remaining_time = SOCHM.remaining_time_to_open;
		}

		if ( typeof SOCHM.timer_design != 'undefined' && SOCHM.timer_design === '2' )
		{
			// Set up FlipDown
			var flipdown = new FlipDown( Number( new Date().getTime() / 1000 ) + Number( remaining_time ) )

			// Start the countdown
			.start()

			// Do something when the countdown ends
			.ifEnded( () =>
			{
				console.log( 'The countdown has ended!' );

				location.reload();
			} );

			return;
		}
		else if ( typeof SOCHM.timer_design != 'undefined' && SOCHM.timer_design === '3' )
		{
			$( '#sochm-days .circle_animation' ).css( 'stroke-dashoffset', 0 );
			
			$( '#sochm-hours .circle_animation' ).css( 'stroke-dashoffset', 0 );
			
			$( '#sochm-minutes .circle_animation' ).css( 'stroke-dashoffset', 0 );
			
			$( '#sochm-seconds .circle_animation' ).css( 'stroke-dashoffset', 0 );
		}

		var remaining_time_timer = setInterval( function()
		{
			if( remaining_time <= 0 )
			{
				clearInterval( remaining_time_timer );

				location.reload();
			}

			if( $( $target ).length < 1 )
			{				
				clearInterval( remaining_time_timer );
			}

			// Time calculations for days, hours, minutes and seconds
			var days = Math.floor( remaining_time / 86400 );
			var hours = Math.floor( remaining_time / 3600 );
			var minutes = Math.floor( remaining_time / 60 % 60 );
			var seconds = Math.floor( remaining_time % 60 );

			days = days < 10 ? '0' + days : days;
			hours = hours < 10 ? '0' + hours : hours;
			minutes = minutes < 10 ? '0' + minutes : minutes;
			seconds = seconds < 10 ? '0' + seconds : seconds;

			if ( typeof SOCHM.timer_design != 'undefined' && SOCHM.timer_design === '0' )
			{
				$( $target ).html( `${days}d:${hours}h:${minutes}m:${seconds}s` );
			}

			if ( typeof SOCHM.timer_design != 'undefined' && SOCHM.timer_design === '1' )
			{
				$( "#sochm-days" ).html( days + "<span>Days</span>" );
				
				$( "#sochm-hours" ).html( hours + "<span>Hours</span>" );
				
				$( "#sochm-minutes" ).html( minutes + "<span>Minutes</span>");
				
				$( "#sochm-seconds" ).html( seconds + "<span>Seconds</span>" );
			}
			else if ( typeof SOCHM.timer_design != 'undefined' && SOCHM.timer_design === '3' )
			{
				$( "#sochm-days > span" ).html( days + " Days" );
				
				$( "#sochm-hours > span" ).html( hours + " Hours" );
				
				$( "#sochm-minutes > span" ).html( minutes + " Minutes");
				
				$( "#sochm-seconds > span" ).html( seconds + " Seconds" );
			}
			else if ( typeof SOCHM.timer_design != 'undefined' && SOCHM.timer_design === '4' )
			{
				$( "#sochm-days > span" ).html( days + " Days" );
				
				$( "#sochm-hours > span" ).html( hours + " Hours" );
				
				$( "#sochm-minutes > span" ).html( minutes + " Minutes");
				
				$( "#sochm-seconds > span" ).html( seconds + " Seconds" );

				$( '#sochm-days .circle_animation' ).css( 'stroke-dashoffset', days * 14.33 );
				
				$( '#sochm-hours .circle_animation' ).css( 'stroke-dashoffset', hours * 18.33 );
				
				$( '#sochm-minutes .circle_animation' ).css( 'stroke-dashoffset', minutes * 7.33 );
				
				$( '#sochm-seconds .circle_animation' ).css( 'stroke-dashoffset', seconds * 7.33 );
			}

			remaining_time -= 1;

		}, 1000 );
	}

	if ( $( '#store_is_going_to_close_soon_remaining_time' ).length )
	{
		if ( $( '#sochm-timer-design-circular-border' ).length && $( '#sochm-timer-design-circular-border > #store_is_going_to_close_soon_remaining_time > div svg' ).length < 1 )
		{
			$( '#sochm-timer-design-circular-border > #store_is_going_to_close_soon_remaining_time > div' ).append( '<svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#6fdb6f" fill="none"/></g></svg>' );
		}

		calculate_countdown( '#store_is_going_to_close_soon_remaining_time' );
	}

	if ( $( '#store_is_going_to_open_soon_remaining_time' ).length )
	{
		if ( $( '#sochm-timer-design-circular-border' ).length && $( '#sochm-timer-design-circular-border > #store_is_going_to_open_soon_remaining_time > div svg' ).length < 1 )
		{
			$( '#sochm-timer-design-circular-border > #store_is_going_to_open_soon_remaining_time > div' ).append( '<svg width="160" height="160" xmlns="http://www.w3.org/2000/svg"><g><circle id="circle" class="circle_animation" r="70" cy="81" cx="81" stroke-width="8" stroke="#6fdb6f" fill="none"/></g></svg>' );
		}

		calculate_countdown( '#store_is_going_to_open_soon_remaining_time' );
	}

	if ( typeof SOCHM.toast_html != 'undefined' )
	{
		$( 'body' ).append( SOCHM.toast_html );

		SOCHM_TOAST.show( { message: SOCHM.toast_message, type: 'error' } );

		if ( $( '#store_is_going_to_open_soon_remaining_time' ).length )
		{
			calculate_countdown( '#store_is_going_to_open_soon_remaining_time' );
		}

		if ( $( '#store_is_going_to_close_soon_remaining_time' ).length )
		{
			calculate_countdown( '#store_is_going_to_close_soon_remaining_time' );
		}
	}

	if ( typeof SOCHM.dialog_html != 'undefined' )
	{
		$( 'body' ).append( SOCHM.dialog_html );

		$( "#sochm-dialog" ).dialog();

		if ( $( '#store_is_going_to_open_soon_remaining_time' ).length )
		{
			calculate_countdown( '#store_is_going_to_open_soon_remaining_time' );
		}

		if ( $( '#store_is_going_to_close_soon_remaining_time' ).length )
		{
			calculate_countdown( '#store_is_going_to_close_soon_remaining_time' );
		}
	}

	if ( typeof SOCHM.sticky_header_html != 'undefined' )
	{
		$( 'body' ).prepend( SOCHM.sticky_header_html );

		if ( $( '#store_is_going_to_open_soon_remaining_time' ).length )
		{
			calculate_countdown( '#store_is_going_to_open_soon_remaining_time' );
		}

		if ( $( '#store_is_going_to_close_soon_remaining_time' ).length )
		{
			calculate_countdown( '#store_is_going_to_close_soon_remaining_time' );
		}
	}

	if ( typeof SOCHM.sticky_footer_html != 'undefined' )
	{
		$( 'body' ).append( SOCHM.sticky_footer_html );

		if ( $( '#store_is_going_to_open_soon_remaining_time' ).length )
		{
			calculate_countdown( '#store_is_going_to_open_soon_remaining_time' );
		}

		if ( $( '#store_is_going_to_close_soon_remaining_time' ).length )
		{
			calculate_countdown( '#store_is_going_to_close_soon_remaining_time' );
		}
	}

	if ( typeof SOCHM.single_page != 'undefined' )
	{
		$( 'body' ).append( SOCHM.single_page );

		if ( $( '#store_is_going_to_open_soon_remaining_time' ).length )
		{
			calculate_countdown( '#store_is_going_to_open_soon_remaining_time' );
		}

		if ( $( '#store_is_going_to_close_soon_remaining_time' ).length )
		{
			calculate_countdown( '#store_is_going_to_close_soon_remaining_time' );
		}
	}

	$( document ).on( 'click', '.sochm-icon-close', function( event )
	{
		$( this ).closest( 'div' ).remove();
	} );
} );

