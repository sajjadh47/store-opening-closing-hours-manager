/*jshint esversion: 6 */

/**
 * SOCHM_TOAST Object
 *
 * Provides a simple toast notification functionality.
 */
var SOCHM_TOAST = {
	/**
	 * Displays a toast notification with a given message and type.
	 *
	 * @param {object} options - An object containing the message and type of the toast.
	 * message: The message to display in the toast.
	 * type:    The type of toast ('success', 'error', or 'warning').
	 */
	show: function( options ) {
		// Get the toast element and icon container using jQuery.
		var toast = jQuery( '#sochm-toast' );

		// Add a class to make the toast visible.
		jQuery( toast ).addClass( 'sochm-toast-visible' );

		// Set the message text in the toast.
		jQuery( '#sochm-toast .sochm-toast-message .sochm-toast-text-2' ).html( options.message );

		// Apply specific styling based on the toast type.
		switch ( options.type ) {
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
};

/**
 * Determines the type of a given object.  This function handles
 * differences in how 'typeof' and Symbol work across different
 * JavaScript environments.
 *
 * @param {object} obj The object to determine the type of.
 * @returns {string} The type of the object (e.g., 'number', 'string', 'object', 'symbol').
 */
function sochm_typeof( obj ) {
	if ( typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ) {
		return typeof obj;
	} else {
		return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
	}
}

/**
 * Ensures that a function is called as a constructor.  If not,
 * it throws a TypeError.
 *
 * @param {object} instance    The 'this' value of the function being called.
 * @param {function} Constructor The constructor function.
 */
function sochm_classCallCheck( instance, Constructor ) {
	if ( ! ( instance instanceof Constructor ) ) {
		throw new TypeError( "Cannot call a class as a function" );
	}
}

/**
 * Defines properties on an object.  This is a utility function
 * for adding non-standard properties to an object, such as
 * getters/setters or non-enumerable properties.
 *
 * @param {object} target The object on which to define properties.
 * @param {array} props  An array of property descriptors.  Each descriptor
 * is an object with 'key', and optionally 'enumerable',
 * 'configurable', 'writable', and 'value' properties.
 */
function sochm_defineProperties( target, props ) {
	for (var i = 0; i < props.length; i++) {
		var descriptor = props[ i ];
		descriptor.enumerable = descriptor.enumerable || false;
		descriptor.configurable = true;
		if ( "value" in descriptor ) descriptor.writable = true;
		Object.defineProperty( target, descriptor.key, descriptor );
	}
}

/**
 * Creates a class with constructor, prototype properties, and static properties.
 * This utility function simplifies defining classes with methods and static
 * members in older JavaScript environments.
 *
 * @param {function} Constructor The constructor function for the class.
 * @param {array} protoProps  An array of property descriptors for prototype methods.
 * @param {array} staticProps An array of property descriptors for static methods.
 * @returns {function} The constructor function.
 */
function sochm_createClass( Constructor, protoProps, staticProps ) {
	if ( protoProps ) sochm_defineProperties( Constructor.prototype, protoProps );
	if ( staticProps ) sochm_defineProperties( Constructor, staticProps );
	return Constructor;
}

/**
 * FlipDown Class
 *
 * Provides a countdown timer that displays days, hours, minutes, and seconds
 * using a flipping animation.
 */
var FlipDown = ( function () {
	/**
	 * Constructor for the FlipDown class.
	 *
	 * @param {number} uts The Unix timestamp representing the end time of the countdown.
	 * @param {string|HTMLElement} el The ID of the element to attach the FlipDown instance to,
	 * or the HTMLElement itself.  Default: 'flipdown'.
	 * @param {object} opt An object containing configuration options.
	 * theme:    The theme of the FlipDown instance ('dark' or 'light').
	 * headings: An array of strings for the labels of the time units
	 * (e.g., ['Days', 'Hours', 'Minutes', 'Seconds']).
	 */
	function FlipDown( uts ) {
		var el = arguments.length > 1 && arguments[ 1 ] !== undefined ? arguments[ 1 ] : "flipdown";
		var opt = arguments.length > 2 && arguments[ 2 ] !== undefined ? arguments[ 2 ] : {};

		sochm_classCallCheck( this, FlipDown );

		// Check if the provided timestamp is a number.
		if ( typeof uts !== "number" ) {
			throw new Error( "FlipDown: Constructor expected unix timestamp, got ".concat( sochm_typeof( uts ), " instead." ) );
		}

		// Handle the case where the element is passed as an object.
		if ( sochm_typeof( el ) === "object" ) {
			opt = el;
			el = "flipdown";
		}

		// Initialize instance properties.
		this.version                 = "0.3.2";
		this.initialised             = false;
		this.now                     = this._getTime(); // Current time in seconds.
		this.epoch                   = uts; // Target time in seconds.
		this.countdownEnded          = false;
		this.hasEndedCallback        = null; // Callback function to execute when the countdown ends.
		this.element                 = document.getElementById( el ); // The container element for the FlipDown instance.
		this.rotors                  = []; // Array to store the rotor elements.
		this.rotorLeafFront          = [];
		this.rotorLeafRear           = [];
		this.rotorTops               = [];
		this.rotorBottoms            = [];
		this.countdown               = null; // Interval timer for the countdown.
		this.daysRemaining           = 0;
		this.clockValues             = {}; // Object to store the current time values (days, hours, etc.).
		this.clockStrings            = {};
		this.clockValuesAsString     = [];
		this.prevClockValuesAsString = [];
		this.opts                    = this._parseOptions( opt ); // Parsed options.

		this._setOptions(); // Apply the options.
	}

	// Define the prototype methods for the FlipDown class.
	sochm_createClass( FlipDown, [
		/**
		 * Starts the countdown timer.
		 *
		 * @returns {FlipDown} Returns the FlipDown instance for chaining.
		 */
		{
			key: "start",
			value: function start() {
				if ( ! this.initialised ) this._init(); // Initialize if not already initialized.
				this.countdown = setInterval( this._tick.bind( this ), 1000 ); // Start the timer.
				return this;
			}
		},
		/**
		 * Sets a callback function to be executed when the countdown ends.
		 *
		 * @param {function} cb The callback function.
		 * @returns {FlipDown} Returns the FlipDown instance for chaining.
		 */
		{
			key: "ifEnded",
			value: function ifEnded( cb ) {
				this.hasEndedCallback = function () {
					cb(); // Execute the callback.
					this.hasEndedCallback = null; // Reset the callback.
				};
				return this;
			}
		},
		/**
		 * Gets the current time in seconds since the Unix epoch.
		 *
		 * @private
		 * @returns {number} The current time in seconds.
		 */
		{
			key: "_getTime",
			value: function _getTime() {
				return new Date().getTime() / 1000;
			}
		},
		/**
		 * Checks if the countdown has ended.  If it has, it executes the
		 * hasEndedCallback, if set.
		 *
		 * @private
		 * @returns {boolean} True if the countdown has ended, false otherwise.
		 */
		{
			key: "_hasCountdownEnded",
			value: function _hasCountdownEnded() {
				if ( this.epoch - this.now < 0 ) {
					this.countdownEnded = true; // Set the flag.

					if ( this.hasEndedCallback != null ) {
						this.hasEndedCallback(); // Execute the callback.
						this.hasEndedCallback = null; //prevent multiple calls
					}

					return true;
				} else {
					this.countdownEnded = false;
					return false;
				}
			}
		},
		/**
		 * Parses the options provided to the constructor.
		 *
		 * @private
		 * @param {object} opt The options object.
		 * @returns {object} The parsed options.
		 */
		{
			key: "_parseOptions",
			value: function _parseOptions( opt ) {
				var headings = [ "Days", "Hours", "Minutes", "Seconds" ]; // Default headings.

				// Override headings if provided in the options.
				if ( opt.headings && opt.headings.length === 4 ) {
					headings = opt.headings;
				}

				return {
					theme: opt.hasOwnProperty( "theme" ) ? opt.theme : "dark", // Default theme is 'dark'.
					headings: headings
				};
			}
		},
		/**
		 * Sets the options for the FlipDown instance.  Currently, this just
		 * sets the theme.
		 *
		 * @private
		 */
		{
			key: "_setOptions",
			value: function _setOptions() {
				this.element.classList.add( "flipdown__theme-".concat( this.opts.theme ) ); // Add theme class.
			}
		},
		/**
		 * Initializes the FlipDown instance.  This method creates the rotor
		 * elements, sets up the initial time values, and starts the timer.
		 *
		 * @private
		 * @returns {FlipDown} Returns the FlipDown instance.
		 */
		{
			key: "_init",
			value: function _init() {
				this.initialised = true;

				// Calculate the number of days remaining.
				if ( this._hasCountdownEnded() ) {
					this.daysremaining = 0;
				} else {
					this.daysremaining = Math.floor( ( this.epoch - this.now ) / 86400 ).toString().length;
				}

				// Determine the number of day rotors to create (2 for <= 2 days, otherwise the number of digits).
				var dayRotorCount = this.daysremaining <= 2 ? 2 : this.daysremaining;

				// Create the rotor elements.  We need rotors for days, hours, minutes, and seconds.
				for (var i = 0; i < dayRotorCount + 6; i++) {
					this.rotors.push( this._createRotor( 0 ) ); // Initial value of 0.
				}

				// Create the day rotor group.
				var dayRotors = [];
				for (var j = 0; j < dayRotorCount; j++ ) {
					dayRotors.push( this.rotors[ j ] );
				}
				this.element.appendChild( this._createRotorGroup( dayRotors, 0 ) ); // 0 for days

				// Create the hour, minute, and second rotor groups.
				var count = dayRotorCount;
				for (var k = 0; k < 3; k++) {
					var otherRotors = [];
					for (var l = 0; l < 2; l++) {
						otherRotors.push( this.rotors[ count ] );
						count++;
					}
					this.element.appendChild( this._createRotorGroup( otherRotors, k + 1 ) ); // 1 for hours, 2 for minutes, 3 for seconds
				}

				// Get references to the rotor sub-elements for easier manipulation.
				this.rotorLeafFront = Array.prototype.slice.call( this.element.getElementsByClassName( "rotor-leaf-front" ) );
				this.rotorLeafRear  = Array.prototype.slice.call( this.element.getElementsByClassName( "rotor-leaf-rear" ) );
				this.rotorTop       = Array.prototype.slice.call( this.element.getElementsByClassName( "rotor-top" ) );
				this.rotorBottom    = Array.prototype.slice.call( this.element.getElementsByClassName( "rotor-bottom" ) );

				this._tick(); // Initial update of the time values.
				this._updateClockValues( true ); // Initial display of the time.

				return this;
			}
		},
		/**
		 * Creates a rotor group element.  A rotor group contains the rotors
		 * for a specific time unit (e.g., days, hours).
		 *
		 * @private
		 * @param {array} rotors      An array of rotor elements.
		 * @param {number} rotorIndex The index of the rotor group (0 for days, 1 for hours, etc.).
		 * @returns {HTMLElement} The rotor group element.
		 */
		{
			key: "_createRotorGroup",
			value: function _createRotorGroup( rotors, rotorIndex ) {
				var rotorGroup       = document.createElement( "div" );
				rotorGroup.className = "rotor-group";

				// Create the heading element for the rotor group.
				var dayRotorGroupHeading       = document.createElement( "div" );
				dayRotorGroupHeading.className = "rotor-group-heading";
				dayRotorGroupHeading.setAttribute( "data-before", this.opts.headings[ rotorIndex ] ); //set the label
				rotorGroup.appendChild( dayRotorGroupHeading );

				sochm_appendChildren( rotorGroup, rotors ); // Add the rotors to the group.
				return rotorGroup;
			}
		},
		/**
		 * Creates a single rotor element.  A rotor displays a single digit
		 * of the time.
		 *
		 * @private
		 * @param {number} v The initial value of the rotor.
		 * @returns {HTMLElement} The rotor element.
		 */
		{
			key: "_createRotor",
			value: function _createRotor() {
				var v = arguments.length > 0 && arguments[ 0 ] !== undefined ? arguments[ 0 ] : 0; // Default value is 0.

				var rotor                 = document.createElement( "div" );
				var rotorLeaf             = document.createElement( "div" );
				var rotorLeafRear         = document.createElement( "figure" );
				var rotorLeafFront        = document.createElement( "figure" );
				var rotorTop              = document.createElement( "div" );
				var rotorBottom           = document.createElement( "div" );

				rotor.className           = "rotor";
				rotorLeaf.className       = "rotor-leaf";
				rotorLeafRear.className   = "rotor-leaf-rear";
				rotorLeafFront.className  = "rotor-leaf-front";
				rotorTop.className        = "rotor-top";
				rotorBottom.className     = "rotor-bottom";

				rotorLeafRear.textContent = v; // Initial value.
				rotorTop.textContent      = v;
				rotorBottom.textContent   = v;

				sochm_appendChildren( rotor, [ rotorLeaf, rotorTop, rotorBottom ] ); // Assemble the rotor.
				sochm_appendChildren( rotorLeaf, [ rotorLeafRear, rotorLeafFront ] ); // Assemble the leaf.
				return rotor;
			}
		},
		/**
		 * Updates the time values.  This method calculates the days, hours,
		 * minutes, and seconds remaining until the target time.
		 *
		 * @private
		 */
		{
			key: "_tick",
			value: function _tick() {
				this.now = this._getTime(); // Get the current time.
				var diff = this.epoch - this.now <= 0 ? 0 : this.epoch - this.now; // Ensure non-negative difference.

				// Calculate the time values.
				this.clockValues.d = Math.floor( diff / 86400 ); // Days (86400 seconds in a day)
				diff -= this.clockValues.d * 86400;
				this.clockValues.h = Math.floor( diff / 3600 ); // Hours (3600 seconds in an hour)
				diff -= this.clockValues.h * 3600;
				this.clockValues.m = Math.floor( diff / 60 ); // Minutes (60 seconds in a minute)
				diff -= this.clockValues.m * 60;
				this.clockValues.s = Math.floor( diff ); // Seconds

				this._updateClockValues(); // Update the display.
				this._hasCountdownEnded(); // Check if the countdown has ended.
			}
		},
		/**
		 * Updates the displayed time values in the rotors.  This method
		 * handles the flipping animation.
		 *
		 * @private
		 * @param {boolean} init Whether this is the initial update (no animation).
		 */
		{
			key: "_updateClockValues",
			value: function _updateClockValues() {
				var _this = this;

				var init = arguments.length > 0 && arguments[ 0 ] !== undefined ? arguments[ 0 ] : false;

				// Pad the time values with leading zeros.
				this.clockStrings.d = sochm_pad( this.clockValues.d, 2 );
				this.clockStrings.h = sochm_pad( this.clockValues.h, 2 );
				this.clockStrings.m = sochm_pad( this.clockValues.m, 2 );
				this.clockStrings.s = sochm_pad( this.clockValues.s, 2 );

				// Convert the time values to an array of strings.
				this.clockValuesAsString = ( this.clockStrings.d + this.clockStrings.h + this.clockStrings.m + this.clockStrings.s ).split( "" );

				// Update the front and bottom rotor faces with the previous values.
				this.rotorLeafFront.forEach( function ( el, i ) {
					el.textContent = _this.prevClockValuesAsString[ i ];
				} );
				this.rotorBottom.forEach( function ( el, i ) {
					el.textContent = _this.prevClockValuesAsString[ i ];
				} );

				/**
				 * Updates the top rotor faces with the current values.
				 */
				function rotorTopFlip() {
					var _this2 = this;
					this.rotorTop.forEach( function ( el, i ) {
						if ( el.textContent != _this2.clockValuesAsString[ i ] ) {
							el.textContent = _this2.clockValuesAsString[ i ];
						}
					} );
				}

				/**
				 * Updates the rear rotor faces with the current values and triggers the flip animation.
				 */
				function rotorLeafRearFlip() {
					var _this3 = this;
					this.rotorLeafRear.forEach( function ( el, i ) {
						if ( el.textContent != _this3.clockValuesAsString[ i ] ) {
							el.textContent = _this3.clockValuesAsString[ i ];
							el.parentElement.classList.add( "flipped" ); // Add the 'flipped' class to trigger the animation.
							var flip = setInterval( function () {
								el.parentElement.classList.remove( "flipped" ); // Remove the class after the animation.
								clearInterval( flip );
							}.bind( _this3 ), 500 ); // 500ms matches the CSS animation duration.
						}
					} );
				}

				// Trigger the flip animation, or update without animation for initial setup.
				if ( ! init ) {
					setTimeout( rotorTopFlip.bind( this ), 500 ); // Delay the top update.
					setTimeout( rotorLeafRearFlip.bind( this ), 500 ); // Start the flip after the top update.
				} else {
					rotorTopFlip.call( this ); // Update immediately.
					rotorLeafRearFlip.call( this ); // Update immediately.
				}

				// Store the current values for the next update.
				this.prevClockValuesAsString = this.clockValuesAsString;
			}
		}
	] );

	return FlipDown;
} )();

/**
 * Pads a number with leading zeros to a specified length.
 *
 * @param {number} n   The number to pad.
 * @param {number} len The desired length of the padded string.
 * @returns {string} The padded string.
 */
function sochm_pad( n, len ) {
	n = n.toString();
	return n.length < len ? sochm_pad( "0" + n, len ) : n;
}

/**
 * Appends multiple children to a parent element.
 *
 * @param {HTMLElement} parent   The parent element.
 * @param {array} children An array of child elements to append.
 */
function sochm_appendChildren( parent, children ) {
	children.forEach( function ( el ) {
		parent.appendChild( el );
	} );
}

// jQuery document ready function.  This ensures that the code runs after the DOM is fully loaded.
jQuery( document ).ready( function ( $ ) {
	/**
	 * Calculates and displays the countdown timer.
	 *
	 * @param {string} $target The jQuery selector of the element where the timer will be displayed.
	 */
	var calculate_countdown = function ( $target ) {
		// Determine which remaining time variable to use (close or open).
		var remaining_time = 0;

		if ( typeof StoreOpeningClosingHoursManager.remainingTimeToClose != 'undefined' ) {
			remaining_time = StoreOpeningClosingHoursManager.remainingTimeToClose;
		}

		if ( typeof StoreOpeningClosingHoursManager.remainingTimeToOpen != 'undefined' ) {
			remaining_time = StoreOpeningClosingHoursManager.remainingTimeToOpen;
		}

		// Use FlipDown timer if timerDesign is 2
		if ( typeof StoreOpeningClosingHoursManager.timerDesign != 'undefined' && StoreOpeningClosingHoursManager.timerDesign === '2' ) {
			// Set up FlipDown
			var flipdown = new FlipDown( Number( new Date().getTime() / 1000 ) + Number( remaining_time ) )

				// Start the countdown
				.start()

				// Do something when the countdown ends
				.ifEnded( () => {
					console.log( 'The countdown has ended!' );
					$.get( StoreOpeningClosingHoursManager.ajaxurl, { action: 'sochm_flush_cache' }, function( data ) {
						location.reload(); // Reload the page.
					} );
				} );

			return; // Exit the function, as FlipDown handles the timer.
		}
		//reset css if design 3 is selected
		else if ( typeof StoreOpeningClosingHoursManager.timerDesign != 'undefined' && StoreOpeningClosingHoursManager.timerDesign === '3' ) {
			$( '#sochm-days .circle_animation' ).css( 'stroke-dashoffset', 0 );

			$( '#sochm-hours .circle_animation' ).css( 'stroke-dashoffset', 0 );

			$( '#sochm-minutes .circle_animation' ).css( 'stroke-dashoffset', 0 );

			$( '#sochm-seconds .circle_animation' ).css( 'stroke-dashoffset', 0 );
		}

		// Set up an interval timer to update the countdown every second.
		var remaining_time_timer = setInterval( function () {
			if ( remaining_time <= 0 ) {
				clearInterval( remaining_time_timer ); // Stop the timer when time is up.
				$.get( StoreOpeningClosingHoursManager.ajaxurl, { action: 'sochm_flush_cache' }, function( data ) {
					location.reload(); // Reload the page.
				} );
			}

			if ( $( $target ).length < 1 ) {
				clearInterval( remaining_time_timer ); // Stop if target element doesn't exist.
			}

			// Time calculations for days, hours, minutes and seconds
			var days    = Math.floor( remaining_time / 86400 );
			var hours   = Math.floor( remaining_time / 3600 );
			var minutes = Math.floor( remaining_time / 60 % 60 );
			var seconds = Math.floor( remaining_time % 60 );

			// Add leading zeros if necessary.
			days    = days < 10 ? '0' + days :days;
			hours   = hours < 10 ? '0' + hours : hours;
			minutes = minutes < 10 ? '0' + minutes : minutes;
			seconds = seconds < 10 ? '0' + seconds : seconds;

			// Update the HTML element with the formatted time.
			if ( typeof StoreOpeningClosingHoursManager.timerDesign != 'undefined' && StoreOpeningClosingHoursManager.timerDesign === '0' ) {
				$( $target ).html( `${ days }d:${ hours }h:${ minutes }m:${ seconds }s` );
			}

			if ( typeof StoreOpeningClosingHoursManager.timerDesign != 'undefined' && StoreOpeningClosingHoursManager.timerDesign === '1' ) {
				$( "#sochm-days" ).html( days + "<span>" + StoreOpeningClosingHoursManager.daysTxt + "</span>" );

				$( "#sochm-hours" ).html( hours + "<span>" + StoreOpeningClosingHoursManager.hoursTxt + "</span>" );

				$( "#sochm-minutes" ).html( minutes + "<span>" + StoreOpeningClosingHoursManager.minutesTxt + "</span>" );

				$( "#sochm-seconds" ).html( seconds + "<span>" + StoreOpeningClosingHoursManager.secondsTxt + "</span>" );
			} else if ( typeof StoreOpeningClosingHoursManager.timerDesign != 'undefined' && StoreOpeningClosingHoursManager.timerDesign === '3' ) {
				$( "#sochm-days > span" ).html( days + " " + StoreOpeningClosingHoursManager.daysTxt );

				$( "#sochm-hours > span" ).html( hours + " " + StoreOpeningClosingHoursManager.hoursTxt );

				$( "#sochm-minutes > span" ).html( minutes + " " + StoreOpeningClosingHoursManager.minutesTxt );

				$( "#sochm-seconds > span" ).html( seconds + " " + StoreOpeningClosingHoursManager.secondsTxt );
			}
			else if ( typeof StoreOpeningClosingHoursManager.timerDesign != 'undefined' && StoreOpeningClosingHoursManager.timerDesign === '4' ) {
				$( "#sochm-days > span" ).html( days + " " + StoreOpeningClosingHoursManager.daysTxt );

				$( "#sochm-hours > span" ).html( hours + " " + StoreOpeningClosingHoursManager.hoursTxt );

				$( "#sochm-minutes > span" ).html( minutes + " " + StoreOpeningClosingHoursManager.minutesTxt );

				$( "#sochm-seconds > span" ).html( seconds + " " + StoreOpeningClosingHoursManager.secondsTxt );

				$( '#sochm-days .circle_animation' ).css( 'stroke-dashoffset', days * 14.33 );

				$( '#sochm-hours .circle_animation' ).css( 'stroke-dashoffset', hours * 18.33 );

				$( '#sochm-minutes .circle_animation' ).css( 'stroke-dashoffset', minutes * 7.33 );

				$( '#sochm-seconds .circle_animation' ).css( 'stroke-dashoffset', seconds * 7.33 );
			}

			remaining_time -= 1; // Decrement the remaining time.

		}, 1000 ); // Update every second.
	};

	$.get( StoreOpeningClosingHoursManager.ajaxurl, { action: 'sochm_get_remaining_time' }, function( data ) {
		StoreOpeningClosingHoursManager = data.data;

		// Display toast message, if provided.
		if ( typeof StoreOpeningClosingHoursManager.toastHtml != 'undefined' && StoreOpeningClosingHoursManager.toastHtml != '' ) {
			$( 'body' ).append( StoreOpeningClosingHoursManager.toastHtml ); // Append the toast HTML to the body.
			SOCHM_TOAST.show( { message: StoreOpeningClosingHoursManager.toastMessage, type: 'error' } ); // Show the toast.

			// Initialize countdown timers for close/open soon messages, if present.  This seems redundant.
			if ( $( '#store_is_going_to_open_soon_remaining_time' ).length ) {
				calculate_countdown( '#store_is_going_to_open_soon_remaining_time' );
			}

			if ( $( '#store_is_going_to_close_soon_remaining_time' ).length ) {
				calculate_countdown( '#store_is_going_to_close_soon_remaining_time' );
			}
		}

		// Display dialog, if provided.
		if ( typeof StoreOpeningClosingHoursManager.dialogHtml != 'undefined' && StoreOpeningClosingHoursManager.dialogHtml != '' ) {
			$( 'body' ).append( StoreOpeningClosingHoursManager.dialogHtml ); // Append the dialog HTML to the body.
			$( "#sochm-dialog" ).dialog(); // Initialize the jQuery UI dialog.

			// Initialize countdown timers for close/open soon messages, if present.  This also seems redundant.
			if ( $( '#store_is_going_to_open_soon_remaining_time' ).length ) {
				calculate_countdown( '#store_is_going_to_open_soon_remaining_time' );
			}

			if ( $( '#store_is_going_to_close_soon_remaining_time' ).length ) {
				calculate_countdown( '#store_is_going_to_close_soon_remaining_time' );
			}
		}

		// Display sticky header, if provided.
		if ( typeof StoreOpeningClosingHoursManager.stickyHeaderHtml != 'undefined' && StoreOpeningClosingHoursManager.stickyHeaderHtml != '' ) {
			$( 'body' ).prepend( StoreOpeningClosingHoursManager.stickyHeaderHtml ); // Prepend the sticky header HTML to the body.

			// Initialize countdown timers. Redundant?
			if ( $( '#store_is_going_to_open_soon_remaining_time' ).length ) {
				calculate_countdown( '#store_is_going_to_open_soon_remaining_time' );
			}

			if ( $( '#store_is_going_to_close_soon_remaining_time' ).length ) {
				calculate_countdown( '#store_is_going_to_close_soon_remaining_time' );
			}
		}

		// Display sticky footer, if provided.
		if ( typeof StoreOpeningClosingHoursManager.stickyFooterHtml != 'undefined' && StoreOpeningClosingHoursManager.stickyFooterHtml != '' ) {
			$( 'body' ).append( StoreOpeningClosingHoursManager.stickyFooterHtml ); // Append the sticky footer HTML to the body.

			// Initialize countdown timers. Redundant?
			if ( $( '#store_is_going_to_open_soon_remaining_time' ).length ) {
				calculate_countdown( '#store_is_going_to_open_soon_remaining_time' );
			}

			if ( $( '#store_is_going_to_close_soon_remaining_time' ).length ) {
				calculate_countdown( '#store_is_going_to_close_soon_remaining_time' );
			}
		}

		// Display single page content, if provided.
		if ( typeof StoreOpeningClosingHoursManager.singlePageHtml != 'undefined' && StoreOpeningClosingHoursManager.singlePageHtml != '' ) {
			$( 'body' ).append( StoreOpeningClosingHoursManager.singlePageHtml ); // Append the single page HTML to the body.

			// Initialize countdown timers. Redundant?
			if ( $( '#store_is_going_to_open_soon_remaining_time' ).length ) {
				calculate_countdown( '#store_is_going_to_open_soon_remaining_time' );
			}

			if ( $( '#store_is_going_to_close_soon_remaining_time' ).length ) {
				calculate_countdown( '#store_is_going_to_close_soon_remaining_time' );
			}
		}

		// Display WooCommerce Notice content, if provided.
		if ( typeof StoreOpeningClosingHoursManager.wcNoticeHtml != 'undefined' ) {
			if ( $( '.woocommerce-notices-wrapper ul.woocommerce-error' ).length ) {
				$( '.woocommerce-notices-wrapper ul.woocommerce-error' ).append( StoreOpeningClosingHoursManager.wcNoticeHtml ); // Append the WooCommerce Notice HTML to the .woocommerce-notices-wrapper.
			} else {
				$( '.woocommerce-notices-wrapper' ).append( '<ul class="woocommerce-error" role="alert" tabindex="-1">' + StoreOpeningClosingHoursManager.wcNoticeHtml + '</ul>' ); // Append the WooCommerce Notice HTML to the .woocommerce-notices-wrapper.
			}

			// Initialize countdown timers. Redundant?
			if ( $( '#store_is_going_to_open_soon_remaining_time' ).length ) {
				calculate_countdown( '#store_is_going_to_open_soon_remaining_time' );
			}

			if ( $( '#store_is_going_to_close_soon_remaining_time' ).length ) {
				calculate_countdown( '#store_is_going_to_close_soon_remaining_time' );
			}
		}

		// Event handler for closing SOCHM elements (toast, dialog, etc.).
		$( document ).on( 'click', '.sochm-icon-close', function ( event ) {
			$( this ).closest( 'div' ).remove(); // Remove the parent element of the close icon.
			$( 'body, html' ).attr( 'style', 'overflow: initial !important;' );
		} );
	} );
} );