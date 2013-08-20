function frequency(arr) {
    var a = [], b = [], prev;

    arr.sort();
    for ( var i = 0; i < arr.length; i++ ) {
        if ( arr[i] !== prev ) {
            a.push(arr[i]);
            b.push(1);
        } else {
            b[b.length-1]++;
        }
        prev = arr[i];
    }

    return [a, b];
}


jQuery(document).ready( function( $ ){

	var form = $( '#filterPageSearch' );

	$( '#propertyFilter' ).isotope({
		itemSelector : '.property',
		layoutMode : 'straightDown'
	});

	$.each( form.find('.range'), function() {
		var min = $(this).attr('data-min');
		var max = $(this).attr('data-max');
		var id = $(this).attr( 'id' );
		var name = $(this).attr( 'data-name' );
		var $values = $('.range-values[data-id="' + id + '"]');

		var current_min = $(this).attr('data-value_min');
		var current_max = $(this).attr('data-value_max');

		$(this).noUiSlider({
		    range: [min, max]
		   ,start: [min, max]
		   ,step: ( max - min < 100 ) ? 1 : 20
		   ,slide: function(){
		      var values = $(this).val();
		      $values.find('.min .value').html( number_format( parseFloat( values[0] ) ) );
		      $values.find('.max .value').html( number_format( parseFloat( values[1] ) ) );
			  filter_properties()
		   }
		   ,serialization : {
		   	to: [ name + '[min]', name + '[max]' ],
		   	resolution: 1
		   }
		});

		if( current_min != '' && current_max != '' ) {
			$(this).val([current_min, current_max])
			$values.find('.min .value').html( number_format( parseFloat( current_min ) ) );
		    $values.find('.max .value').html( number_format( parseFloat( current_max ) ) );
		}

	})



	form.find( 'select' ).on( 'change', function() {
		filter_properties();
	})

	form.find( 'input[type="text"]' ).on( 'keyup', function() {
		filter_properties();
	})

	$(document).on( 'change', 'input[type="checkbox"]', function() {
		filter_properties();
	})



	function filter_properties() {

		serialized = form.serializeArray();
		options = [];
		$.each( serialized, function( i, item ) {
			item = $(this);

			item = item[0];
			value = item.value;

			filter_type = $( '*[name="'+item.name+'"]:first' ).parents( '.filter-section' ).attr('data-filter-type');

			if( typeof item.name == 'undefined' || item.name == null ) {
				item.name = '';
			}

			if( value != '' && value != null && typeof value != 'undefined' ) {
				value = value.replace(" ","-");
			}

			if( item.name.substr( -2 ) == '[]' ) {
				name = item.name.substr( 0, item.name.length - 2 );
				if( !( name in options ) ) {
					options[name] = [];
					options[name]['type'] = 'array',
					options[name]['values'] = [];
					options[name]['values'].push( value )
				}
				else {
					options[name]['values'].push(value)
				}
				options[name]['filter_type'] = filter_type;
			}
			else if( item.name.substr( -5 ) == '[min]' ) {
				name = item.name.substr( 0, item.name.length - 5 );
				if( !( name in options ) ) {
					options[name] = [];
					options[name]['type'] = 'range';
				}
				options[name]['filter_type'] = filter_type;
				options[name]['min'] = value
			}
			else if( item.name.substr( -5 ) == '[max]' ) {
				name = item.name.substr( 0, item.name.length - 5 );
				if( !( name in options ) ) {
					options[name] = [];
					options[name]['type'] = 'range';
				}
				options[name]['filter_type'] = filter_type;
				options[name]['max'] = value
			}
			else {
				name = item.name;
				if( !( name in options ) ) {
					options[name] = [];
					options[name]['type'] = 'value';
					options[name]['value'] = value
					options[name]['filter_type'] = filter_type;
				}

			}


		})


		property_ids = [];
		i = 0;
		for (var name in options ) {
			var option = options[name];
			if( option.type === 'value' ) {
				if( option.value == '' ) {
					properties = $( '.property' );
				}
				else {
					classname = '.property.filter_' + name + '-' + option.value;
					properties = $( classname );
				}
				property_ids[i] = [];
				$.each( properties, function() {
					property_ids[i].push( $(this).attr( 'data-id' ) )
				})
			}
			else if( option.type === 'array' ) {
				property_array = []

				if( option.filter_type == 'or' ) {
					$.each( option.values, function( i, value ) {
						classname = '.property.filter_' + name + '-' + value
						properties = $( classname );
						$.each( properties, function() {
							property_array.push( $(this).attr( 'data-id' ) )
						})
					})
				}
				else {
					classname = '';
					$.each( option.values, function( i, value ) {
						classname = classname + '.property.filter_' + name + '-' + value
					})

					properties = $( classname );
					$.each( properties, function() {
						property_array.push( $(this).attr( 'data-id' ) )
					})

				}

				var property_array = property_array.filter(function(itm,i,property_array){
				    return i==property_array.indexOf(itm);
				});


				property_ids[i] = property_array;
			}
			else if( option.type === 'range' ) {
				property_array = []
				min = parseFloat( option.min );
				max = parseFloat( option.max );
				$.each( $( '#propertyFilter .property' ), function() {
					value = parseFloat( $(this).attr( 'data-' + name ) );
					if( value >= min && value <= max ) {
						property_array.push( $(this).attr( 'data-id' ) )
					}
				})
				property_ids[i] = property_array;
			}
			i++;
		}

		var has_zero = 0;

		$.each( property_ids, function() {
			if( this.length == 0 ) {
				has_zero = 1;
			}
		})


		properties = [];
		freq = 0;
		$.each( property_ids, function( i, ids ) {
			if( ids != '' && ids != null && typeof ids != 'undefined' ) {
				freq++;
			}
			properties = properties.concat( ids );
		})

		properties = frequency(properties);

		properties_to_show = [];

		for ( var i in properties[1] ) {
			if( properties[1][i] == freq ) {
				properties_to_show.push( '.post-' + properties[0][i] );
			}
		}

		properties_to_show = properties_to_show.join( ',' )
		if( properties_to_show == '' || properties_to_show == null || typeof properties_to_show == 'undefined' || has_zero == 1 ) {
			properties_to_show = '.noneatall';
			$('#filterNoResults').fadeIn()
		}
		else {
			$('#filterNoResults').fadeOut()
		}

		$( '#propertyFilter' ).isotope({ filter : properties_to_show })

	}

})

