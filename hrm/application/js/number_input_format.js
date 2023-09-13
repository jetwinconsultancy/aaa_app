// console.log("number_input_formats.js");

// $(".input_num_val").keydown(function(e){

// 	if(!((e.keyCode > 95 && e.keyCode < 106)
//       || (e.keyCode > 47 && e.keyCode < 58) 
//       || e.keyCode == 8)) {
//         return false;
//     }

// });

$('.input_num_val').mask('#,##0.00', {
	reverse: true,
	translation: {
	    '#': {
	      	pattern: /-|\d/,
	      	recursive: true
	    }
	},
	onChange: function(value, e) {
	    var target = e.target,
	        position = target.selectionStart; // Capture initial position

	    target.value = value.replace(/(?!^)-/g, '').replace(/^,/, '').replace(/^-,/, '-');

	    target.selectionEnd = position; // Set the cursor back to the initial position.
	}
});


