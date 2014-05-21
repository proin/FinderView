var url = ".";
var click_location = "";

$(document).ready(function() {
	loadViewer();

	$('#btn-home').click(function(){
		location.reload();
	});
});

function loadViewer(uri) {
	displayToViewer(null);
	$.ajax({
		url : url+"/api.php",
		dataType : "jsonp",
		jsonp : "callback",
		data : {
			type : 'get',
			mode : '0',
			folder : uri
		},
		success : function(data) {
			displayToViewer(data);
		}
	});
}

function displayToViewer(data) {
	var targets = [$('#item-1'),$('#item-2'),$('#item-3'),$('#item-4'),$('#item-5')];
	for(var i=0;i<targets.length;i++)
		displaySingleLine(targets[i], null);
	if(data == null)
		return;

	for(var i=0;i<data.length;i++) {
		displaySingleLine(targets[i], data[i]);
	}

}

function displaySingleLine(target, data) {
	if(data==null) {
		target.html('');
		return;
	}

	var innerHtml = '';

	if(data.folders.length > 0) {
		innerHtml += '<table class="table table-hover"><thead><tr><th>Folder</th><th></th></tr></thead><tbody>';
		for(var i=0;i<data.folders.length;i++) {
			var folder_name = data.folders[i].name;
			folder_name = folder_name.replace(/_/gi, "<wbr>_<wbr>");
			if(click_location.indexOf(data.folders[i].folderuri_nobase) != -1)
				innerHtml += '<tr id="'+target.attr('id')+'-folder-'+i+'" value="'+data.folders[i].folderuri+'" loc="'+data.folders[i].folderuri_nobase+'" class="folder-clicked items"><td>' + folder_name + '</td><td class="text-right"><span class="glyphicon glyphicon-chevron-right"></span></td></tr>';
			else
			innerHtml += '<tr id="'+target.attr('id')+'-folder-'+i+'" value="'+data.folders[i].folderuri+'" loc="'+data.folders[i].folderuri_nobase+'" class="items"><td>' + folder_name + '</td><td class="text-right"><span class="glyphicon glyphicon-chevron-right"></span></td></tr>';
		}
		innerHtml += '</tbody></table>';
	}

	if(data.files.length > 0) {
		innerHtml += '<table class="table table-hover"><thead><tr><th>File</th></tr></thead><tbody>';
		for(var i=0;i<data.files.length;i++) {
			var file_name = data.files[i].name;
			file_name = file_name.replace(/_/gi, "<wbr>_<wbr>");
			innerHtml += '<tr id="'+target.attr('id')+'-file-'+i+'" class="items"><td><a href=".'+data.files[i].link+'" target="_blank">' + file_name + '</a></td></tr>';
		}
		innerHtml += '</tbody></table>';
	}

	target.html(innerHtml);

	for(var i=0;i<data.folders.length;i++) {
		$("#"+target.attr('id')+'-folder-'+i).click(function() {
			click_location = $(this).attr('loc');
 			loadViewer($(this).attr('value'));
		});
	}
}
