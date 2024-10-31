( function ( blocks, element ) {
	var quest_class = 'questo-should-be-inserted-here';

	blocks.registerBlockType( 'questpass/widget', {
		title: 'Questpass',
		icon: element.createElement(
			'svg',
			{ viewBox: "0 0 256 207.19", class: 'dashicon' },
			element.createElement( 'polygon', { points: '133.44 103.59 29.84 0 0 29.84 73.75 103.59 0 177.4 29.84 207.19 133.44 103.59' } ),
			element.createElement( 'polygon', { points: '256 103.59 152.41 0 122.56 29.84 196.37 103.59 122.56 177.4 152.41 207.19 256 103.59' } )
		),
		category: 'embed',
		edit: function () {
			return element.createElement( 'div', { className: quest_class } );
		},
		save: function () {
			return element.createElement( 'div', { className: quest_class } );
		},
	} );
} )( window.wp.blocks, window.wp.element );
