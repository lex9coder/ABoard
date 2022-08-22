var current_level = 0;
var catalogs;
var category_current = 0;
function showSelectCategory(id)
{
	var subcategory = false;
	current_level++;

	$wrap = $('.categiry-selected');
	$tmpl = $('.category_select_tmpl').clone();
	$('.cat', $tmpl).remove();
	$tmpl_row = $('.category_select_tmpl .cat').eq(0);


	var title = 'Категория';
	if( id>0 ) {
		$.each(catalogs, function (i, item){
			if( item.id == id ) {
				title = item.title;
				$tmpl.attr('cat_id', id);
				return false;
			}
		});
	}

	$('.title', $tmpl).html(title);
	$tmpl.attr('level', current_level);

	$.each(catalogs, function (i, item){
		if( item.parent_id == id ) {
			$tmpl_row.html( item.title );
			$tmpl_row.attr('cat_id', item.id );
			$tmpl.append($tmpl_row.clone());
			subcategory = true;
		}
	});

	if (subcategory==true) {
		var clone = $tmpl.clone().removeClass('hide').removeClass('category_select_tmpl').addClass('show');
		$wrap.append(clone);
		return false;
	}
	return true;
}

function getCatalogPathTitle()
{
	var path = '';
	$('.category_select_list .cat.active').each(function(i, item){
		if (path) {
			path = path + ' / ';
		}
		path = path + $(this).html();
	});
	return path;
}

function loadForm(id) {
	$.ajax({
		type: "GET",
		url: '/api/offer/get_form',
		data: {},
		success: function(data){
			if(data.status == 'ok') {
				var wrap = $('.offer-add-form_wrapper');
				wrap.html(data.html);
			}
		},
		dataType: 'json'
	});
}

(function ($) {
	"use strict";

	// add offer
	// get categories
	$.ajax({
		type: "GET",
		url: '/api/offer/get_category',
		data: {},
		success: function(data){
			if(data.status == 'ok') {
				catalogs = data.result;
				showSelectCategory(0);
			}
		},
		dataType: 'json'
	});
	
	//   select category
	$(document).on('click', '.category_select_list .cat', function(){
		var obj = $(this);
		current_level = $(this).parent().attr('level');
		$('.category_select_list').each(function(i, item){
			if( $(this).hasClass('category_select_tmpl') == false
				&& $(this).attr('level')>current_level ) {
				$(this).remove();
			}
		});

		obj.parent().find('.cat').removeClass('active');
		obj.addClass('active');

		var id = obj.attr('cat_id');
		category_current = 0;
		var last_catalog = showSelectCategory(id)
		if( last_catalog ) {
			var catalog_path_titles = getCatalogPathTitle();
			// console.log(catalog_path_titles);
			loadForm(id);
			category_current = id;
		}
		return false;
	});


	$(document).on('click', '#form_offer_add_save_btn', function(){
		$('[name=category_id]').val(category_current);
	});

})(jQuery);


// Offer
(function ($) {
	"use strict";

	$(document).on('click', '#btn_offer_phone_show', function(){

		var obj = $(this);
		var offer_id = obj.siblings('[name=offer_id]').val();
		$.ajax({
			type: "GET",
			url: '/api/offer/get_phone',
			data: {'id':offer_id},
			success: function(data){
				if(data.status == 'ok') {
					obj.html(data.phone);
				}
			},
			dataType: 'json'
		});
		return false;
	});

})(jQuery);
