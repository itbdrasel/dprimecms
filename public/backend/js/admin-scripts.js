/**
 * App Main Scripts
 * ------------------
 * Use only for function calls and Plugin calls.
 */

if ($('#windowmodal').length ) {

	$('.modal').on('shown.bs.modal', function (e) {
		var target = $(e.relatedTarget);
		$.ajax({
			url: target[0].href,
			success: function(response)
			{
				$('#windowmodal .modal-content').html(response);
				$('#windowmodal .modal-title').html('<input type="hidden" id="modal_hidden" value="1">');
			}
		});
		// $(this).find('.modal-content').load(target[0].href);
	});

	$(".modal").on("hide.bs.modal", function (e) {
		if ($('.dismiss').data('reload')) {
			location.reload();
		} else {
			$(".modal-body").html("");
		}

	});
}

$(document).ready(function(){

	//remove empty url params when form submitted by get method
	$('form.filter').submit(function() {
		$(this).find(":input").filter(function(){return !this.value;}).attr("disabled", "disabled");
	});


	// table sorting event.
	if ($('.sort').length ) {

		$('.sort').on('click', function() {
			var order = 1;
			//defin asc or desc
			if(urlParam('order') == 1 ) order = 2;
			else if(urlParam('order') == 2) order = 1;

			window.location.href = pageUrl+"?by="+$(this).data('row')+"&order="+order;
		});
		var modal_status =true;
		if ($('#modal_hidden').length ) {
			if ($('##modal_hidden').val() ==1){
				var modal_status =false;
			}
		}
		if (modal_status == true){
			if(urlParam('order') == 1){
				$("#"+urlParam('by') ).append('&nbsp;<i class="fa fa-arrow-up"><i>');
			}else if(urlParam('order') == 2){
				$("#"+urlParam('by') ).append('&nbsp;<i class="fa fa-arrow-down"><i>');
			}else{
				$("#name" ).append('&nbsp;<i class="fa fa-arrow-up"><i>');
			}
		}
	}


	$('.sidebar-toggle-btn').PushMenu({
			'noTransitionAfterReload' : true,
		});


});





  if ($('.editor-min').length ) {
 /**
 * Editor
 * Minimal Settings
 **/
  tinymce.init({
    selector: '.editor-min',
    height: 200,
	menubar:false,
    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | ' +
      'bullist numlist',

  });

  }




 if ($('.editor').length ) {
 /**
 * Base Tinyymce editor
 * Maximum Settings
 **/

	tinymce.init({
		selector: '.editor',
		height: 600,
		allow_script_urls: true,
		relative_urls : false,
		remove_script_host: true,
		document_base_url: APP_URL,
		plugins: [
		'advlist autolink link image imagetools lists charmap print preview hr anchor pagebreak spellchecker',
		'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
		'table emoticons templates paste help'
		],
		toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
		'bullist numlist outdent indent | link image | print preview media fullpage | ' +
		'forecolor backcolor emoticons | help',
		menu: {
		favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | spellchecker | emoticons'}
		},
		menubar: 'favs file edit view insert format tools table help',
		//content_css: base_url+'css/design.css',
		image_advtab: true,
		image_uploadtab: true,
		paste_data_images: true,
		automatic_uploads: true,
		valid_children : "+a[div], +body[style]",
		extended_valid_elements : "i[id|class|style],em,span[*]",		
		
		file_picker_callback: filePicker,

	});

 }


 function filePicker(callback, value, meta) {

	var fmUrl, title;

	switch(meta.filetype){
		case 'image':
			fmUrl = APP_URL + '/author/mediamanager/';
			title = 'Media Manager'
			break;
		case 'file':
			fmUrl = APP_URL + '/author/mediamanager';
			title = "Link Picker"
			break;			
		default:
			fmUrl = APP_URL + '/author/mediamanager/';
	}

	tinyMCE.activeEditor.windowManager.openUrl({
		url : fmUrl,
		title : title,
		width : wH('x') * 0.8,
		height : wH('y') * 0.8,
		resizable : "yes",
		close_previous : "no",
		onMessage: (api, data) => {
			if (data.mceAction === 'mceGeturl') {
			  callback(data.url);
			  api.close();
		  }				  
		}			
	});

}



if($('#featured').length ) {

	$('#featured').on( 'click', function(){ 
		var newWindow;
		var theTop=(screen.height/2)-(500/2);
		var theLeft=(screen.width/2)-(1100/2);

		newWindow = window.open(APP_URL + "/author/mediamanager", '_blank', 'location=0,height=400,width=1100,scrollbars=no,status=no,top='+theTop+',left='+theLeft+'');
		newWindow.focus();
	}); 

	//feature image link to the featuredimage field;
	function setValue(val) {
		inputField = document.getElementById("featuredimage");
		fieldValue = inputField.value;

		if(fieldValue.length) inputField.value = fieldValue+'|'+val;
		else inputField.value = val;

		processFile(inputField.value);		
	}

/***
 * create selected image preview
 * as well as validate max number of image input.
*/
	function processFile(imagePath){
		var imgPathArr = new Array();
		imgPathArr = imagePath.split('|');
		if(imgPathArr.length <= 4){			
			var preview = '';
			for(var i = 0; i < imgPathArr.length; i++ ){
				preview += '<span class="m"><span class="x">x</span><img src="'+imgPathArr[i]+ '" width=55 height=45 /></span>';
			}
		}else alert("Maximam 4 images can be used!");
		
		$('.preview').html(preview);	
	}	


} //end featured

$(document).on("click",'.preview .x',function(){
	var s; var im; var imgs;
	s = $(this).next().attr('src');				
	im = $('#featuredimage').val();
	imgs = im.split('|');										
	if(imgs.length > 1){				
		if(imgs[0] == s){
			im = im.replace(s+'|','');
		}else{
			im = im.replace('|'+s,'');
		}					
	}else{ im = im.replace(s,'');}
			
	$('#featuredimage').val(im);
	$(this).siblings().remove();
	$(this).unwrap().remove();												  
});		


if( $('#metades').length){

	$('#metades').keyup(function () {
		var max = 300;
		var min = 180;
		var len = $(this).val().length;
		if ( len <= max && len >= min) {
			$('#chars').html('Characters: <span style="color:green"> '+len+' </span> (180 - 300 characters is ideal.)');
		} else {
			$('#chars').html('Characters: <span style="color:red"> '+len+' </span> (180 - 300 characters is ideal.)');
		}
	});
}


//get widow with and height
function wH(d){
	var x, y;
	if(d === 'x')
		return x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
	else
		return y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;
}


$.ajaxPrefilter(function( options, original_Options, jqXHR ) {
    options.async = true;
});


if ( $('.datepicker, .dateOfBirth, .toDayDatePicker, .checkoutDatePicker').length) {

}

// Date Picker
if ( $('.datepicker').length){
	$(function () {
		$(".datepicker").datepicker({
			showAnim: "slide",
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			yearRange: "-80:+80"
		});
	});
}
if ( $('.dateOfBirth').length){
	$(function () {
		$(".dateOfBirth").datepicker({
			showAnim: "slide",
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			yearRange: "-80:+0",
			maxDate: '0'
		});
	});
}

if ( $('.toDayDatePicker').length){
	$(function () {
		var date = new Date();
		var hours = date.getHours();
		var day = '0';
		if (hours >=0 && hours <11){
			day = '-1'
		}
		$(".toDayDatePicker").datepicker({
			showAnim: "slide",
			dateFormat: 'yy-mm-dd',
			minDate: day
		});
	});
}

if ( $('.tomorrowDatePicker').length){
	$(function () {
		$(".tomorrowDatePicker").datepicker({
			showAnim: "slide",
			dateFormat: 'yy-mm-dd',
			minDate: 1
		});
	});
}


if ( $('.checkoutDatePicker').length){
	$(function () {	var date = new Date();
		var hours = date.getHours();
		var day = '1';
		if (hours >=0 && hours <11){
			day = '0'
		}
		$(".checkoutDatePicker").datepicker({
			showAnim: "slide",
			dateFormat: 'yy-mm-dd',
			minDate: day
		});
	});
}


if ($('#display-btn').length) {
	$('#display-btn').click(function () {
		$('.btn-d-none').fadeToggle();
	});
}

if ( $('.timePicker').length) {
	(function($) {
			"use strict";
			$(function(){
				$('.timePicker').timepicker({
					'scrollDefault': 'now',
					'timeFormat': 'h:i A',
					'step':15
				});
			});
		}
	)(jQuery);
}

function printData(print_aria_id_name)
{
	var divContents = $("#"+print_aria_id_name).html();
	var header = $("#header_aria").html();
	var printWindow = window.open('', '', 'left=0,top=0,right=0,bottom=0,width=screen.width,height=screen.height,margin=0,0,0,0');
	var WindowDoc = printWindow.document;
	WindowDoc.write('<html>');
	WindowDoc.write(header);
	WindowDoc.write('<body >');
	WindowDoc.write('<style media="print"> body {font-size: 14pt;font-family:Tahoma,sans-serif;} </style>');
	WindowDoc.write(divContents);
	WindowDoc.write('</body></html>');
	WindowDoc.close();
	printWindow.focus();
	printWindow.print();
	WindowDoc.close();
}


if ( $('.onlyNumber').length) {
	$('.onlyNumber').on('keyup', function (e) {
		if (/\D/g.test(this.value)) {
			this.value = this.value.replace(/\D/g, '');
		}
	});
}



if ($('#alias').length ) {
	$('#alias').on('blur',function () {
		getAlias($(this).val());
	})
}
if ($('#title').length ) {
	$('#title').on('blur',function () {
		getAlias($(this).val());
	})
}

function getAlias(title) {
	$.ajax({
		url: APP_URL +"/author/get-alias",
		type: "get",
		data: {title: title},
		success: function (data) {
			$('#alias').val(data);
		}
	});
}