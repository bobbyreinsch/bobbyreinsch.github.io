// JavaScript Document
jQuery(function($){
	//make cities draggable
	$('a.trip').draggable({
		cursor:'move',
		containment:'.container',
		stop:getLocPosition
	});
	
// update image position to css and options	
function getLocPosition(event, ui){
	var posX = parseInt(ui.position.left);
	var posY = parseInt(ui.position.top);
	var mapW = parseInt($('.map').innerWidth());
	var mapH = parseInt($('.map').innerHeight());
	//change to CSS percentage
	var LocT = (posY/mapH)*100;
	var LocL = (posX/mapW)*100;
	
	$(this).css('top',LocT+'%');
	$(this).css('left',LocL+'%');
	
	var slug = $(this).attr('id');
	var Ltop = 'field-'+slug+'-top';
	var Lleft = 'field-'+slug+'-left';
	
	$('#map_admin input.'+Ltop).val(LocT.toFixed(2)+'%');
	$('#map_admin input.'+Lleft).val(LocL.toFixed(2)+'%');
	
	/* Add to option fields
	$(this).find('.pos>.t').html(LocT.toFixed(2));
	$(this).find('.pos>.l').html(LocL.toFixed(2));
	*/
	
}

// Background Image Upload	
	$('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            $('#image_url').val(image_url);
        });
    });
	
});