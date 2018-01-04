function resizeBg(element, small_img_arr, smallest_img_arr){
	this.image_small = small_img_arr;
	this.image_smallest = smallest_img_arr;
	this.w_width;
	this.element = element;
	this.init = function(){
		var obj = this;
		obj.w_width = jQuery(window).width();
		
		obj.resBg(obj);
		// jQuery(window).resize(function() {
		//     obj.resBg(obj);     
		// });
	};
	this.resBg = function(obj){
		if(obj.w_width<420){
        	obj.element.each(function(i, val){
        		jQuery(this).css("background-image", "url(" + obj.image_smallest[i] + ")");
        	});
        }else if(obj.w_width>420 && obj.w_width<780){
            obj.element.each(function(i, val){

        		jQuery(this).css("background-image", "url(" + obj.image_small[i] + ")");
        	});
        }else{
            // do nothing as main image is already loaded via css
        }
	}
}