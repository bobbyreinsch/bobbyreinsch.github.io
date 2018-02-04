
	// init controller
	jQuery(document).ready(function( $ ) {	

	// image sequence for voting
	//var v_img = ["img/image-select-1.png","img/image-select-2.png","img/image-select-3.png"]; //moved to functions.php

	if ( typeof v_img !== 'undefined' && v_img ){

	var obj = {curImg: 0};
	var voting = TweenMax.to(obj, 0.5, {
			curImg: v_img.length - 1,
			roundProps: "curImg",
			repeat: 0,
			immediateRender: true,
			ease: Linear.easeNone,
			onUpdate: function(){
				$(".selectbox > img").attr("src",v_img[obj.curImg]);
			}
		})


	var controller = new ScrollMagic.Controller();

	new ScrollMagic.Scene({triggerElement: ".step1",duration: 180})
					.setClassToggle(".row-1", "active") // add class toggle
					//.addIndicators() // add indicators (requires plugin)
					.addTo(controller);
	new ScrollMagic.Scene({triggerElement: ".step2",duration: 325})
					.setClassToggle(".row-2, .selectbox", "active") // add class toggle
					.setTween(voting)
					//.addIndicators() // add indicators (requires plugin)
					.addTo(controller);
	new ScrollMagic.Scene({triggerElement: ".step3",duration: 216})
					.setClassToggle(".row-3", "active") // add class toggle
					//.addIndicators() // add indicators (requires plugin)
					.addTo(controller);
	new ScrollMagic.Scene({triggerElement: ".row-4",duration: 180})
					.setPin(".pictures")
					.setClassToggle(".row-4", "active") // add class toggle
					//.addIndicators() // add indicators (requires plugin)
					.addTo(controller);
	new ScrollMagic.Scene({triggerElement: ".row-4",duration: 420})
					.setClassToggle(".step4", "active") // add class toggle
					//.addIndicators() // add indicators (requires plugin)
					.addTo(controller);
	new ScrollMagic.Scene({triggerElement: ".step5",duration: 0})
					.setClassToggle(".step5, .glow-6, .imac", "active") // add class toggle
					//.addIndicators() // add indicators (requires plugin)
					.addTo(controller);

	}

	});

