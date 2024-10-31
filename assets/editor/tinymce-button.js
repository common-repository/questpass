tinymce.PluginManager.add( 'questpass_button', function ( editor, url ) {
	var quest_class = 'questo-should-be-inserted-here';

	var set_content_editable = function () {
		tinymce.activeEditor.dom.setAttrib(
			tinymce.activeEditor.dom.select( '.' + quest_class ),
			'contenteditable',
			false
		);
	};

	editor.addButton( 'questpass_button', {
		title: 'Questpass',
		cmd: 'questpass_button',
		image: url + '/../img/questpass-sygnet.png'
	} );

	editor.addCommand( 'questpass_button', function () {
		tinymce.activeEditor.dom.remove( tinymce.activeEditor.dom.select( '.' + quest_class ) );
		editor.execCommand( 'mceReplaceContent', false, '<div class="' + quest_class + '"></div>' );
		set_content_editable();
	} );

	editor.on( 'setContent', set_content_editable );
} );
