jQuery( document ).ready( function( $ )
{
	$( "#loginform" ).css( 'position', 'relative' );

	$( "input[type='submit']" ).click( function( event )
	{
		event.preventDefault();

		$( "#loginform" ).addClass( 'updating' );

		var redirect = jQuery( "input[name='redirect_to'" ).val() == '' ? window.location.origin : jQuery( "input[name='redirect_to'" ).val();

		var data =
		{
			'action'	: 'wpla_login_ajax',
			'login'		: $( "input#user_login" ).val(),
			'pass'		: $( "input#user_pass" ).val(),
			'rememberme': ''
		};

		if ( $(  "input#rememberme" ).is( ':checked' ) )
		{
			data['rememberme'] = $( "input#rememberme" ).val();
		}

		$.post( wpla_plugin_ajax_url, data, function( response )
		{
			if ( response.wp_error )
			{
				$( "#loginform" ).removeClass( 'updating' );

				if ( $( "#login .message" ).length )
				{	
					$( "#login .message" ).remove();
				}

				if ( $( "#login #login_error" ).length )
				{
					$( "#login #login_error" ).html( response.wp_error );
				}
				else
				{
					$( "form#loginform" ).before( '<div id="login_error">' + response.wp_error + '</div>' );

				}
			}
			else if( response.wp_success )
			{
				$( "#loginform" ).removeClass( 'updating' );

				if ( $( "#login .message" ).length )
				{	
					$( "#login .message" ).remove();
				}

				if ( redirect )
				{
					if ( $( "#login #login_error" ).length )
					{
						$( "#login #login_error" ).css( 'border-left-color', '#00a0d2' ).html( response.wp_success );
					}
					else
					{
						$( "form#loginform" ).before( '<p class="message">' + response.wp_success + '</p>' );
					}
					
					window.location.href = redirect;
				}
			}
		});
	});
});