var $ = jQuery.noConflict();
$("#post-720 ul.products").remove();

$('select orderby:contains("Default sorting")').text('Sort By');


$(document).ready(function() {

	//$(".browse_listed_items ul.products li").addClass("browse_product_box_wrap col-xl-3 col-lg-3 col-md-4");
	


	$(".grid-view").click(function(){
		$(".list-view").removeClass("active");
		$(this).addClass("active");
		$(".recently_listed_items .pindex").removeClass("fullrow");
		$(".browse_listed_items .browse_product_box_wrap").removeClass("col-12 fullrow").addClass("col-xl-3 col-lg-3 col-md-4");
	});
	$(".list-view").click(function(){
		$(".grid-view").removeClass("active");
		$(this).addClass("active");
		$(".recently_listed_items .pindex").addClass("fullrow");
		$(".browse_listed_items .browse_product_box_wrap").removeClass("col-xl-3 col-lg-3 col-md-4").addClass("col-12 fullrow");
	});

	$(".product_categories li").find("span.child_plus").append("<span class='plus pull-right slidetoggle-icon'></span>");
	$( ".product_categories .plus" ).click(function() {
		$(this).parent("span").next(".product_list").slideToggle('slow').toggleClass('product_list_child');
		$(this).toggleClass('minus');
	});
	$("span.child_plus span.plus").on("click", function(){
		var category_id = $(this).parent('span').attr('rel');
		console.log(category_id);
		if(category_id!='' && category_id != undefined){
			$(this).addClass('loading');
			var current_class = $(this).attr('class');
			var that = $(this);
			$.ajax({
				url: ajax_obj.ajaxurl, // or example_ajax_obj.ajaxurl if using on frontend
				data: {
					'action': 'ajax_request_product_category',
					'category_id' : category_id
				},
				success:function(data) {
					if(that.parent('span').hasClass('child_plus')){
						//that.removeClass('child_plus');
						//that.toggleClass('minus');
						that.parent('span').removeAttr('rel');
					}
					if(that.hasClass('loading')){
						that.removeClass('loading');

						
					}
					//$( data ).insertAfter( that );
					console.log(data);
					//alert(data);
					//var data_update = '<ul class='product_list'><li><a href=http://localhost/cnp670/product/afghanistan-scott-ra2-mnh-postal-tax-margin-copiy/> Afghanistan Scott RA2 MNH** postal tax  margin copiy </a></li></ul>';
					if(data!=""){
						that.parent().parent().append(data);
						that.parent("span").next(".product_list").slideToggle('slow').toggleClass('product_list_child');
						that.addClass('minus');
					}
					// This outputs the result of the ajax request
					
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});  
			console.log(current_class);
			//alert("The paragraph was clicked.");
		}else{
			//alert('Please enter valid Category');
			//return false;
		}

		});
	
});