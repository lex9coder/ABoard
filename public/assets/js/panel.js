function panelUserShowResult(r)
{
	obj = $('.panel-users-search-result').find('.result');
	obj.html('');
	if(r.length>0) {
		$.each(r, function(k, v){
			var html = "<tr>"
			+ "<td>" + v.id + "</td>"
			+ "<td>" + v.fullnamne + "</td>"
			+ "<td>" + v.email + "</td>"
			+ "<td>" + v.created_at + "</td>"
			+ "<td>" + v.is_verified + "</td>"
			+ "</tr>";
			obj.append(html);
		});
	} else {
		obj.append('<br>Не найдено');
	}
}

function panelUserSearch(term)
{
    $.ajax({
		type: "GET",
		url: '/api/user/search',
		data: { 'term': term},
		success: function(data){
			if(data.status == 'ok') {
				panelUserShowResult(data.result);
			}
		},
		dataType: 'json'
	});
}

$(document).ready(function() {

	var magicalTimeout=500;
    var timeout;

	// panel search users
	$(document).on('keyup', '#panel-users-search-input', function(e){
		var obj = $(this);
		clearTimeout(timeout);
		timeout=setTimeout(panelUserSearch, magicalTimeout, obj.val());
	});
});