var $j = jQuery.noConflict();

$j(document).ready(function() {

  $j("#slides").slidesjs({
    width: 457,
    height: 327,
    navigation: false,
    play: {
	auto: true,
	effect: "fade",
	interval: 5000,
	restartDelay: 2500,
	swap: true
    },
 //    navigation: {
	// effect: "fade"
 //    },
    pagination: {
	// active: true,
	effect: "slide"
    },
    effect: {
	fade: {
		speed: 800
	}
    }
  });

 //  $j("#galslides").slidesjs({
 //    width: 457,
 //    height: 327,
 //    navigation: false,
 //    play: {
 //    auto: true,
 //    effect: "fade",
 //    interval: 5000,
 //    restartDelay: 2500,
 //    swap: true
 //    },
 // //    navigation: {
 //    // effect: "fade"
 // //    },
 //    pagination: {
 //    // active: true,
 //    effect: "slide"
 //    },
 //    effect: {
 //    fade: {
 //        speed: 800
 //    }
 //    }
 //  });

// engraving possibilities
// var frameEngOpt = [ "Wood Top Front", "Wood Bottom Front", "Wood Top & Bottom Front", "Wood Lid", "Glass Top Standard Text", "Glass Top Monogram Letter", "Glass Top Large Last Name", "Glass Bottom Standard Text", "Glass Bottom Monogram Letter", "Glass Bottom Large Last Name", "Glass Centered Standard Text", "Glass Centered Large Monogram Letter", "Glass Centered Large Last Name", "Plaque Top", "Plaque Bottom", "Plaque Lid" ]

// var wood = [ "Wood Top Front", "Wood Bottom Front", "Wood Top & Bottom Front", "Wood Lid" ];
// var glass = [ "Glass Top Standard Text", "Glass Top Monogram Letter", "Glass Top Large Last Name", "Glass Bottom Standard Text", "Glass Bottom Monogram Letter", "Glass Bottom Large Last Name", "Glass Centered Standard Text", "Glass Centered Large Monogram Letter", "Glass Centered Large Last Name " ];
// var plaque = [ "Plaque Top", "Plaque Bottom", "Plaque Lid" ];

// console.log( frameEngOpt );
// console.log( wood );
// console.log( glass );
// console.log( plaque );

// var len = frameEngOpt.length;
// for( i = 0; i < frameEngOpt.length; i++ ) {
    // console.log( "key: " + i + " value: " + frameEngOpt[i] );
// }
// console.log( frameEngOpt.length );
// console.log( $j.inArray( "Glass Bottom Large Last Name", frameEngOpt ) );

// frameEngOpt = $j.grep( )

// var select = $j( "#select_9" );
// console.log( select );

// var engOpt = new Array;
// $j( "#select_9 option" ).each ( function() {
//     engOpt.push( $j( this ).text() );
// });
// console.log( engOpt.join( ',' ) );







// var selects = $j('select');
// selects.on('change', function() {
//     $j("option", selects).prop("disabled", false);
//     selects.each(function() {
//         var select = $j(this),
//               options = selects.not(select).find('option'),
//               selectedText = select.children('option:selected').text();
//         options.each(function() {
//             if($j(this).text() == selectedText) $j(this).prop("disabled", true);
//         });
//     });
// });

// selects.eq(0).trigger('change');
	
});