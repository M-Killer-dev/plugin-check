( function ( data ) {
	const checkItButton = document.getElementById( 'plugin-check__submit' );
	const pluginsList   = document.getElementById( 'plugin-check__plugins-dropdown' );

	// Return early if the elements cannot be found on the page.
	if ( ! checkItButton || ! pluginsList ) {
		console.error( 'Missing form elements on page' );
		return;
	}

	checkItButton.addEventListener( 'click', (e) => {
		e.preventDefault();

		const pluginCheckData = new FormData();

		// Collect the data to pass along for generating a check results.
		pluginCheckData.append( 'action', 'plugin_check_run_checks' );
		pluginCheckData.append( 'nonce', data.nonce );
		pluginCheckData.append( 'plugin', pluginsList.value );

		fetch(
			data.ajaxUrl,
			{
				method: 'POST',
				credentials: 'same-origin',
				body: pluginCheckData
			}
		)
		.then(
			( response ) => {
				if ( ! response.ok ) {
					throw new Error( `${response.status}: ${response.statusText}` );
				}

				return response.json();
			}
		)
		.then(
			( data ) => {
				if ( ! data.success && ( ! data.data || ! data.data.message ) ) {
					throw new Error( 'Response contains no data' );
				}

				console.log( data.data.message );
			}
		)
		.catch(
			( error ) => { console.error( error ); }
		);

	} );

} )( PLUGIN_CHECK ); /* global PLUGIN_CHECK */
