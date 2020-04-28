var FidemoSettings = function( $ ) {
	var self = this,
		timer = '',
		IsSiteInstalled = false,
		isInstalling = false,
		currentSite = {};

	self.spinner = '<span class="fidemo-loading ajax-loader js-fidemo-spinner"></span>';

	jQuery( document ).on( 'click', '.js-fidemo-force-refresh', function( e ) {
		document.location.href = 'admin.php?page=manage-refsites&force_refresh=1';
	} );

	var versionCompare = ( function() {
		var re = /^(<|>|[=!<>]=)?\s*(\d+(?:\.\d+){0,4})([a-z][a-z0-9\-]*)?$/i;

		function get_val( str, include_cmp ) {
			var matches = ( str + '' ).match( re );
			return matches
				? ( include_cmp ? ( matches[1] || '==' ) : '' )

				+ '"'

				+ ( matches[2] + '.0.0' )
					.match( /\d+(?:\.\d+){0,2}/ )[0]
					.replace( /(?:^|\.)(\d+)/g, function( a, b ) {
						return Array( 9 - b.length ).join( 0 ) + b;
					} )

				+ ( matches[3] || '~' )

				+ '"'

				: ( include_cmp ? '==0' : 1 );
		};

		return function( base_ver ) {
			base_ver = get_val( base_ver );
			for (var arg, i = 1; arg = arguments[i++];) {
				if (!( new Function( 'return ' + base_ver + get_val( arg, 1 ) ) )()) {
					return false;
				}
			}
			return true;
		};
	} )();

	jQuery( document ).on( 'mouseover', '.js-fidemo-refsite', function( e ) {
		jQuery( this ).addClass( 'focus' );
	} );

	jQuery( document ).on( 'mouseout', '.js-fidemo-refsite', function( e ) {
		jQuery( this ).removeClass( 'focus' );
	} );

	fidemo_dialog_texts.sites = jQuery.parseJSON( fidemo_dialog_texts.sites );
	var installationSteps = jQuery.parseJSON( fidemo_dialog_texts.installation_steps );
	var existingPlugins = jQuery.parseJSON( fidemo_dialog_texts.existing_plugins );
	var siteIsReady = jQuery( '.fidemo-site-is-not-ready' ).val();

	self.init_dialogs = function() {
		jQuery( 'body' ).append( '<div id="js-fidemo-dialog-container" class="fidemo-dialog-container js-fidemo-dialog-container"></div>' );
		self.dialog_callback = '';
		self.dialog_callback_params = [];
		self.fidemo_dialog = jQuery( '#js-fidemo-dialog-container' ).dialog( {
			dialogClass: 'js-fidemo-dialog fidemo-dialog',
			autoOpen: false,
			modal: true,
			minWidth: 450,
			show: {
				effect: 'blind',
				duration: 800,
			},
			open: function( event, ui ) {
				jQuery( 'body' ).addClass( 'modal-open' );
				if (!fidemo_dialog_texts.is_discover) {
					jQuery( '.js-fidemo-process-button' )
						.addClass( 'button-secondary' )
						.removeClass( 'button-primary ui-button-disabled ui-state-disabled' )
						.prop( 'disabled', true )
						.css( { 'marginLeft': '15px', 'display': 'inline' } );
				}

				if (fidemo_settings.dialog_callback_params.tutorial_url == '') {
					jQuery( '.js-fidemo-tutorial-button' ).hide();
				} else {
					jQuery( '.js-fidemo-tutorial-button' ).show();
				}

			},
			close: function( event, ui ) {
				jQuery( 'body' ).removeClass( 'modal-open' );
				if (IsSiteInstalled) {
					document.location.href = 'admin.php?page=manage-refsites';
				}
			},
			buttons: [
				{
					class: 'button-primary js-fidemo-process-button',
					text: fidemo_dialog_texts.install,
					click: function() {
						if (self.dialog_callback != '') {
							self.dialog_callback.call( null, self.dialog_callback_params );
						}
						jQuery( '#js-fidemo-dialog-container' ).animate( {
							scrollTop: 0,
						}, 'fast' );
					},
				},
				{
					class: 'button-secondary js-fidemo-tutorial-button',
					text: fidemo_dialog_texts.tutorial,
					click: function() {
						window.open( fidemo_settings.dialog_callback_params.tutorial_url );
					},
				},
				{
					class: 'button-secondary ',
					text: fidemo_dialog_texts.live_preview,
					click: function() {
						window.open( fidemo_settings.dialog_callback_params.preview_site );
					},
				},
			],
		} );
	};

	self.init = function() {
		self.init_dialogs();
	};

	self.init();

	jQuery( document ).on( 'change', '.js-fidemo-ready-to-install,.js-fidemo-db-backup', function( e ) {

		if (jQuery( '.js-fidemo-ready-to-install' ).prop( 'checked' ) === true && jQuery( '.js-fidemo-db-backup' ).prop( 'checked' ) === true) {
			fidemo_change_action_button( true );
		} else {
			fidemo_change_action_button( false );
		}
	} );

	jQuery( document ).on( 'click', '.js-fidemo-refsite', function( e ) {
		if (isInstalling) {
			return;
		}
		var current_site_id = jQuery( this ).data( 'site' ),
			canInstall = true,
			allowWpml = false;
		if (siteIsReady == 0) {
			canInstall = false;
		}
		currentSite = fidemo_dialog_texts.sites[current_site_id];

		var description = currentSite.short_description,
			requiredPlugins = '',
			requiredPluginsWpml = '',
			themes = '';

		var siteSize = ( currentSite.downloads['files.zip'] / 1024 / 1024 ).toFixed( 2 ) + ' MB';

		var previewHtml = '<div class="fidemo-refsite-info">' +
			'<div class="fidemo-refsite-image"><img src="' + currentSite.large_image + '"></div>' +
			'<div class="fidemo-refsite-action"><h1>' + currentSite.title + '</h1>' +
			'<span>By OnTheGoSystems </span>' +
			'<p>' + description.replace( /\\"/g, '"' ) + '</p>' +
			'<p><strong>' + fidemo_dialog_texts.site_size_notice.replace( /%s/g, siteSize ) + '</strong></p>';

		var defaultThemeIsSet = false;

		if (typeof currentSite.themes.additional_themes !== 'undefined') {
			var themesExists = false;
			var themeSwitcher = '<h3>' + fidemo_dialog_texts.available_themes + '</h3>' +
				'<ul class="js-fidemo-available-themes">';
			jQuery.each( currentSite.themes.additional_themes, function( index, themeInfo ) {

				var themeSelected = '',
					themeDisabled = '',
					themeMessage = '',
					themeNote = '';
				if (fidemo_dialog_texts.is_multisite && !themeInfo.theme_status) {
					themeDisabled = 'disabled';
					themeMessage = ' <span class="fidemo-plugin-error">(' + fidemo_dialog_texts.theme_not_found + ')</span>';
				} else {
					themesExists = true;
				}

				if ( typeof themeInfo.note !== 'undefined' && themeInfo.note != '' ) {
					themeNote = ' (' + themeInfo.note + ')';
				}

				if (index === currentSite.themes.theme && themeDisabled === '') {
					themeSelected = ' checked="checked"';
					defaultThemeIsSet = true;
				}
				themeSwitcher += '<label><input type="radio" name="multiple-themes" ' + themeDisabled + ' value="' + index + '" ' + themeSelected + '> '
					+ themeInfo.name + themeMessage + themeNote + '</label><br>';

			} );
			themeSwitcher += '</ul>';

			if (fidemo_dialog_texts.is_multisite && !themesExists) {
				previewHtml += '<p><span class="fidemo-plugin-error">' +
					fidemo_dialog_texts.themes_required + '</span></p>';
				canInstall = false;
			} else {
				previewHtml += themeSwitcher;
			}

		} else {
			if (fidemo_dialog_texts.is_multisite && !currentSite.themes.theme_status) {
				previewHtml += '<p><span class="fidemo-plugin-error">' +
					fidemo_dialog_texts.theme_required_single.replace( /%s/g, currentSite.themes.theme ) + '</span></p>';
				canInstall = false;
			}
		}

		jQuery.each( currentSite.plugins, function( index, value ) {
			if (typeof value.title !== 'undefined') {
				var updateRequiredText = '';
				var downloadRequiredText = '';

				var pluginMatch = false;
				jQuery.each( existingPlugins, function( plugin_slug, plugin_info ) {
					if (plugin_info.name == value.title) {
						if (downloadRequiredText === '') {
							var currentVersion = plugin_info.version.replace( /-/g, '' );
							var neededVersion = value.version.replace( /-/g, '' );
							//When a version contains text (eg. beta) allow only exact version
							if (neededVersion.match( /^[0-9\.]+$/ ) === null) {
								if (neededVersion === currentVersion) {
									updateRequiredText = '';
									pluginMatch = true;
									return false;
								} else {
									updateRequiredText = ' ' + fidemo_dialog_texts.plugin_outdated.replace( /%s/g, value.version );
								}
							} else {
								if (!versionCompare( currentVersion, '>=' + neededVersion )) {
									updateRequiredText = ' ' + fidemo_dialog_texts.plugin_outdated.replace( /%s/g, value.version );
								} else {
									updateRequiredText = '';
									pluginMatch = true;
									return false;
								}
							}
						}
					}
				} );

				var plugin_name = value.title;
				var plugin_version = value.version.replace( /-/g, '' );
				var is_beta_plugin = ( plugin_version.match( /^[0-9\.]+$/ ) === null ? true : false );

				if (!pluginMatch) {
					if (/Toolset/.test( value.title ) || /WPML/.test( value.title )) {
						if ( is_beta_plugin ) {
							downloadRequiredText = ' ' + fidemo_dialog_texts.plugin_no_exists_toolset_beta.replace( /%s/g, value.url );
						} else {
							downloadRequiredText = ' ' + fidemo_dialog_texts.plugin_no_exists_toolset.replace( /%s/g, value.url );
						}
					} else {
						downloadRequiredText = ' ' + fidemo_dialog_texts.plugin_no_exists.replace( /%s/g, value.url );
					}
				}

				var addonText = '';
				if (downloadRequiredText !== '') {
					addonText = '<span class="fidemo-plugin-error">' + downloadRequiredText + '</span>';
				}
				if (updateRequiredText !== '') {
					addonText = '<span class="fidemo-plugin-error">' + updateRequiredText + '</span>';
				}
				if (downloadRequiredText === '' && updateRequiredText === '') {
					addonText = ' <i class="fa fa-check" style="font-size:18px;color:green"></i>';
				}

				if ( is_beta_plugin ) {
					plugin_name += ' Beta';

				}
				if (value.title.indexOf( 'WPML' ) === -1 && value.title.indexOf( 'Multilingual' ) === -1) {
					requiredPlugins += '<tr>' +
						'<td><a href="' + value.url + '" target="_blank">' + plugin_name + '</a></td><td>' + addonText + '</td>' +
						'</tr>'
				} else {
					allowWpml = true;
					requiredPluginsWpml += '<tr class="js-fidemo-plugins-list-wpml hidden"><td><a href="' + value.url + '" target="_blank">' +
						value.title + '</a></td><td>' + addonText + '</td></tr>';
				}
			}

		} );

		canInstall = canInstall && fidemo_check_plugins_compatibility( currentSite, false );

		if (allowWpml) {
			previewHtml += '<h3>' + fidemo_dialog_texts.language_select_title + ':</h3>' +
				'<label><input type="radio" name="fidemo-wpml" value="no-wpml" checked="checked"> ' +
				fidemo_dialog_texts.one_language_label +
				'</label><br>' +
				'<label><input type="radio" name="fidemo-wpml" value="wpml"> ' +
				fidemo_dialog_texts.multi_language_label +
				'</label>';
		}

		previewHtml += '<h3>' + fidemo_dialog_texts.required_plugins + '</h3>' +
			'<table withd="100%" class="js-fidemo-plugins-list">' +
			requiredPlugins +
			requiredPluginsWpml +
			'</table>';

		previewHtml += '<div class="fidemo-confirmation-text">';
		if (canInstall) {
			previewHtml += generate_confirmation_text();
		}
		previewHtml += '</div>';

		previewHtml += '</div></div>';
		if (fidemo_dialog_texts.current_installed_site === currentSite.shortname && !fidemo_dialog_texts.is_discover) {
			previewHtml += fidemo_show_notice( fidemo_dialog_texts.site_already_installed, 'fidemo-notice-green' );
		}

		previewHtml += '<div class="fidemo-site-notices">';
		if (!canInstall) {
			previewHtml += fidemo_show_notice( fidemo_dialog_texts.cannot_install_site );
		}
		if (fidemo_dialog_texts.is_discover && !currentSite.can_install) {
			if (window.location.hash.indexOf( 'installsite' ) !== 1) {
				previewHtml += fidemo_show_notice( fidemo_dialog_texts.maximum_alowed_sites.replace( /%s/g, currentSite.title ) );
				fidemo_change_action_button( false );
				canInstall = false;
			}
		}
		previewHtml += '</div>';

		fidemo_settings.fidemo_dialog.html( previewHtml );
		if (typeof currentSite.themes.additional_themes !== 'undefined' && !defaultThemeIsSet) {
			jQuery( 'input[name="multiple-themes"]:not([disabled])' ).first().prop( 'checked', true );
		}
		var dialogHeight = jQuery( window ).height() - 30;
		var dialogWidth = jQuery( window ).width() - 100;
		if (fidemo_dialog_texts.is_discover && !fidemo_settings.run_install) {
			fidemo_settings.dialog_callback = create_site;
		} else {
			fidemo_settings.dialog_callback = installation_preview;
		}

		fidemo_settings.dialog_callback_params = {
			'siteid': jQuery( this ).data( 'site' ),
			'preview_site': currentSite.site_url,
			'tutorial_url': '',
		};
		if (typeof currentSite.tutorial_url !== 'undefined' && currentSite.tutorial_url != '' && currentSite.tutorial_url != '#') {
			fidemo_settings.dialog_callback_params.tutorial_url = currentSite.tutorial_url;
		}

		if (canInstall) {
			fidemo_change_action_button( true );
		}

		fidemo_settings.fidemo_dialog.dialog( 'open' ).dialog( {
			title: currentSite.title,
			width: jQuery( window ).width() - 20,
			closeOnEscape: false,
			heiht: dialogHeight,
			maxHeight: dialogHeight - 40,
			draggable: false,
			resizable: false,
			position: { my: 'left top+20', at: 'left+10 top', of: window },
		} );

	} );

	function generate_confirmation_text() {
		var output = '';
		if (!fidemo_dialog_texts.is_discover) {
			output = '<h2 class="fidemo-installation-notice">' + fidemo_dialog_texts.install_notice + '<br>' + fidemo_dialog_texts.install_notice4 + '</h2>' +
				'<div class="message error"><p>' +
				'<label><input type="checkbox" value="1" class="js-fidemo-db-backup"> ' +
				fidemo_dialog_texts.install_notice3 +
				'</p></div>' +
				'<div class="message error"><p>' +
				'<label><input type="checkbox" value="1" class="js-fidemo-ready-to-install"> ' +
				fidemo_dialog_texts.install_notice2 +
				'</p></div>';
		}
		return output;
	}

	jQuery( document ).on( 'change', 'input[name="fidemo-wpml"]', function( e ) {
		var canInstall = true;

		if (jQuery( this ).val() === 'no-wpml') {
			jQuery( '.js-fidemo-plugins-list-wpml' ).hide();
			canInstall = fidemo_check_plugins_compatibility( currentSite, false );
		} else {
			jQuery( '.js-fidemo-plugins-list-wpml' ).show();
			canInstall = fidemo_check_plugins_compatibility( currentSite, true );
		}

		if (fidemo_dialog_texts.is_discover && !currentSite.can_install) {
			canInstall = false;
			fidemo_change_action_button( false );
			return false;
		}

		if (canInstall) {
			jQuery( '.fidemo-confirmation-text' ).html( generate_confirmation_text() );
			jQuery( '.fidemo-site-notices' ).html( ' ' );
		} else {
			jQuery( '.fidemo-confirmation-text' ).html( ' ' );
			jQuery( '.fidemo-site-notices' ).html( fidemo_show_notice( fidemo_dialog_texts.cannot_install_site ) );
			fidemo_change_action_button( false );
		}
	} );

	function fidemo_show_notice( text, style ) {
		var noticeClass = '';
		if (typeof style !== 'undefined') {
			noticeClass = ' ' + style;
		}
		var output = '<div class="refsite-actions">' +
			'<div class="fidemo-notice' + noticeClass + '">' +
			text +
			'</div></div>';
		return output;
	}

	function fidemo_change_action_button( state ) {
		if (state) {
			jQuery( '.js-fidemo-process-button ' )
				.removeClass( 'button-secondary ui-button-disabled ui-state-disabled' )
				.addClass( 'button-primary' )
				.prop( 'disabled', false );
		} else {
			jQuery( '.js-fidemo-process-button ' )
				.addClass( 'button-secondary  ui-button-disabled ui-state-disabled' )
				.removeClass( 'button-primary' )
				.prop( 'disabled', true );
		}
	}

	var create_site = function() {
		var theme = 'default',
			lang = 'no-wpml';
		if (typeof jQuery( 'input[name="multiple-themes"]:checked' ).val() !== 'undefined') {
			theme = jQuery( 'input[name="multiple-themes"]:checked' ).val();
		}
		if (typeof jQuery( 'input[name="fidemo-wpml"]:checked' ).val() !== 'undefined') {
			lang = jQuery( 'input[name="fidemo-wpml"]:checked' ).val();
		}
		fidemo_settings.fidemo_dialog.dialog( 'close' );
		wpvlive_create_new_site_onclick_handler( currentSite.site_name, currentSite.ID, theme, lang );
		var url = '#TB_inline?height=300&width=750&inlineId=wpvlive_new_site_form&modal=true';
		tb_show( 'Enter a name for your site', url );
	};

	var installation_preview = function() {

		fidemo_settings.dialog_callback_params.wpml = '';
		fidemo_settings.dialog_callback_params.multiple_themes = '';

		if (typeof jQuery( 'input[name="fidemo-wpml"]:checked' ).val() !== 'undefined') {
			fidemo_settings.dialog_callback_params.wpml = jQuery( 'input[name="fidemo-wpml"]:checked' ).val();
		}

		if (typeof jQuery( 'input[name="multiple-themes"]:checked' ).val() !== 'undefined') {
			fidemo_settings.dialog_callback_params.multiple_themes = jQuery( 'input[name="multiple-themes"]:checked' ).val();
		}

		if (fidemo_discover_settings.wpml != '') {
			fidemo_settings.dialog_callback_params.wpml = fidemo_discover_settings.wpml;
		}
		if (fidemo_discover_settings.theme != '' && fidemo_discover_settings.theme != 'default') {
			fidemo_settings.dialog_callback_params.multiple_themes = fidemo_discover_settings.theme;
		}

		var install_html = '<h2>' +
			fidemo_dialog_texts.installation_proccess.replace( /%s/g, currentSite.tagline )
			+ '</h2>' +
			'<ul class="js-fidemo-installation-proccess">';

		jQuery.each( installationSteps, function( index, step ) {
			install_html += '<li>' + step + '</li>';
		} );
		install_html += '</ul>' +
			'<div class="js-fidemo-installation-status"></div>';
		jQuery( '.fidemo-refsite-action' ).html( install_html );

		timer = new Date().getTime();

		fidemo_change_action_button( false );

		proccess_installation_step( 0 );

	};

	var proccess_installation_step = function( step ) {
		isInstalling = true;
		jQuery( '.ui-dialog-titlebar-close' ).hide();
		jQuery( '.js-fidemo-installation-proccess li' ).eq( step ).append( ' <i class="fa fa-cog fa-spin" style="font-size:20px"></i>' );

		var data = {
			action: 'fidemo_installation_proccess',
			wpnonce: fidemo_dialog_texts.fidemo_nonce,
			site_id: fidemo_settings.dialog_callback_params.siteid,
			step: step,
			wpml: fidemo_settings.dialog_callback_params.wpml,
			multiple_themes: fidemo_settings.dialog_callback_params.multiple_themes,
		};

		jQuery.ajax( {
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function( response ) {
				//call step 10 when installation isn't completed successfully
				if (step === 10) {
					jQuery( '.fidemo-notice' ).append( '<div>' + response.data[1] + '</div>' );
					return false;
				}
				jQuery( '.js-fidemo-installation-proccess li' ).eq( step ).find( 'i' ).remove();

				if (response.success) {
					jQuery( '.js-fidemo-installation-proccess li' ).eq( step ).append( ' <i class="fa fa-check" style="font-size:24px;color:green"></i>' );

					if (typeof response.data.nonce !== 'undefined') {
						fidemo_dialog_texts.fidemo_nonce = response.data.nonce;
					}
					if (step < installationSteps.length - 1) {
						proccess_installation_step( step + 1 );
					}
				} else {
					jQuery( '.js-fidemo-installation-proccess li' ).eq( step ).append( ' <i class="fa fa-warning" style="font-size:20px;color:red"></i>' );
					jQuery( '.js-fidemo-installation-status' ).html( fidemo_show_notice( response.data.message ) );

				}

				if (step === 4) {
					IsSiteInstalled = true;
					isInstalling = false;
					jQuery( '.ui-dialog-titlebar-close' ).show();
					jQuery( '.js-fidemo-installation-status' ).html( response.data.message );
					console.log( '<br>Installation time: ' + ( ( new Date().getTime() - timer ) / 1000 ) + ' seconds' );
				}

			},
			fail: function( xhr, textStatus, error ) {
				IsSiteInstalled = true;
				isInstalling = false;
				jQuery( '.js-fidemo-installation-proccess li' ).eq( step ).find( 'i' ).remove();
				jQuery( '.js-fidemo-installation-status' ).html( fidemo_show_notice( error ) );
				jQuery( '.js-fidemo-installation-proccess li' ).eq( step ).append( ' <i class="fa fa-warning" style="font-size:20px;color:red"></i>' );
				jQuery( '.ui-dialog-titlebar-close' ).show();
			},
			statusCode: {
				500: function( xhr, textStatus, error ) {
					jQuery( '.js-fidemo-installation-status' ).html( fidemo_show_notice( error ) );
					jQuery( '.js-fidemo-installation-status' ).append( '<ul class="js-fidemo-installation-proccess">' +
						'<li class="js-fi-restore-database">' + fidemo_dialog_texts.restoring_database + '<i class="fa fa-check" style="font-size:24px;color:green"></i></li></ul>' );
					proccess_installation_step( 10 );
					IsSiteInstalled = true;
					isInstalling = false;
					jQuery( '.js-fidemo-installation-proccess li' ).eq( step ).find( 'i' ).remove();
					jQuery( '.js-fidemo-installation-proccess li' ).eq( step ).append( ' <i class="fa fa-warning" style="font-size:20px;color:red"></i>' );
					jQuery( '.ui-dialog-titlebar-close' ).show();
				},
			},
		} );
	};

	jQuery.each( jQuery( '.js-fidemo-refsite' ), function( index, refsite ) {
		var site_index = jQuery( this ).data( 'site' );
		var can_install = fidemo_check_plugins_compatibility( fidemo_dialog_texts.sites[site_index], false );
		if (fidemo_dialog_texts.is_multisite && can_install) {
			can_install = fidemo_check_themes_compatibility( fidemo_dialog_texts.sites[site_index] );
		}
		if (!can_install) {
			jQuery( this ).find( '.fidemo-refsite-screenshot' ).addClass( 'fidemo-refsite-inactive' );
			jQuery( '<div class="fidemo-refsite-inactive-info">' + fidemo_dialog_texts.requirements_not_met + '</div>' )
				.insertAfter( jQuery( this ).find( '.fidemo-refsite-screenshot img' ) );
		}
		if (fidemo_dialog_texts.is_discover && !fidemo_dialog_texts.sites[site_index].can_install) {
			jQuery( this ).find( '.fidemo-refsite-screenshot' ).addClass( 'fidemo-refsite-inactive' );
			jQuery( '<div class="fidemo-refsite-inactive-info">' + fidemo_dialog_texts.maximum_alowed_sites.replace( /%s/g, fidemo_dialog_texts.sites[site_index].title ) + '</div>' )
				.insertAfter( jQuery( this ).find( '.fidemo-refsite-screenshot img' ) );
		}
	} );

	function fidemo_check_themes_compatibility( current_site ) {
		var canInstall = true;
		if (typeof current_site.themes.additional_themes === 'undefined') {
			if (!current_site.themes.theme_status) {
				canInstall = false;
			}
		} else {
			canInstall = false;
			jQuery.each( current_site.themes.additional_themes, function( index, value ) {
				if (value.theme_status) {
					canInstall = true;
				}
			} );
		}

		return canInstall;
	}

	function fidemo_check_plugins_compatibility( current_site, wpml ) {
		var canInstall = true;
		if (siteIsReady === 0) {
			return false;
		}

		jQuery.each( current_site.plugins, function( index, value ) {

			if (typeof value.title !== 'undefined') {
				if (!wpml && ( value.title.indexOf( 'WPML' ) !== -1 || value.title.indexOf( 'Multilingual' ) !== -1 )) {
					return true;
				}

				var pluginMatch = false;
				jQuery.each( existingPlugins, function( plugin_slug, plugin_info ) {
					if (plugin_info.name == value.title) {
						var requiredVersion = plugin_info.version.replace( /-/g, '' );
						var installedVersion = value.version.replace( /-/g, '' );
						if ( installedVersion.match(/^[0-9\.]+$/) === null ) {
							if ( installedVersion === requiredVersion ) {
								pluginMatch = true;
							}
						} else {
							if (versionCompare( installedVersion, '<=' + requiredVersion )) {
								pluginMatch = true;
							}
						}
					}
				} );

				if (!pluginMatch) {
					canInstall = false;
					return false;
				}
			}

		} );
		return canInstall;
	}

};
var fidemo_discover_settings = { 'wpml': '', 'theme': '' };

jQuery( document ).ready( function( $ ) {
	fidemo_settings = new FidemoSettings( $ );

	var refsite = fidemo_getUrlParameter( 'refsite' );
	if (refsite !== '' && !window.location.hash) {
		jQuery.each( jQuery( '.js-fidemo-refsite' ), function( i, v ) {
			if (jQuery( this ).data( 'shortname' ) == refsite) {
				jQuery( this ).trigger( 'click' );
				return false;
			}
		} );
	}

	if (window.location.hash) {
		if (window.location.hash.indexOf( 'installsite' ) === 1) {
			var fullhash = window.location.hash,
				parts = fullhash.split( '_' ),
				origin = parts[parts.length - 1],
				theme = parts[parts.length - 3],
				version = parts[parts.length - 2],
				site_id = parts[parts.length - 4],
				target_verification = fidemo_dialog_texts.verification_string,
				site_screen = 'admin.php?page=manage-refsites';
			if (typeof fidemo_dialog_texts.current_installed_site !== 'undefined' && fidemo_dialog_texts.current_installed_site != '') {
				return;
			}
			if (origin && target_verification) {

				var the_origin = jQuery.trim( origin );
				var the_target_verification = jQuery.trim( target_verification );
				if (the_target_verification == the_origin) {

					jQuery.each( jQuery( '.js-fidemo-refsite' ), function( i, v ) {
						if (jQuery( this ).data( 'id' ) == site_id) {
							fidemo_discover_settings.wpml = version;
							fidemo_discover_settings.theme = theme;
							fidemo_settings.run_install = true;
							jQuery( this ).trigger( 'click' );
							jQuery( '.js-fidemo-process-button' ).click();
							return false;
						}
					} );
				} else {
					//Redirect to reference sites screen
					window.location = site_screen;
				}
			} else {
				//Redirect to reference sites screen
				window.location = site_screen;
			}
		}
	}
} );

function fidemo_getUrlParameter( name ) {
	name = name.replace( /[\[]/, '\\[' ).replace( /[\]]/, '\\]' );
	var regex = new RegExp( '[\\?&]' + name + '=([^&#]*)' );
	var results = regex.exec( location.search );
	return results === null ? '' : decodeURIComponent( results[1].replace( /\+/g, ' ' ) );
};
