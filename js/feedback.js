//* update 2012-10-18 16:15:00 *//
$(function(){
	$('body').append('<div class="modal hide" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
						 '<div class="modal-header">'+
							'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+
							'<h3>Опишите проблему или ошибку</h3>'+
						  '</div>'+
						  '<div class="modal-body">'+
							'<textarea class="feedback_message" required="required" placeholder="Ваше сообщение"></textarea>'+
						  '</div>'+
						  '<div class="modal-footer">'+
							'<a href="#" class="btn btn-primary" onclick="send_feedback(); return false;">Отправить</a>'+
						'</div></div>');	
	
	
	$('body').click(function(e){
		if (e.ctrlKey){
			$('.modal-body .alert').remove();
			$('.modal-body .feedback_message').html('');
			$('#myModal').modal('show');
		}
	});
})
$(document).keydown(function(e){
	if (e.altKey && e.keyCode == 13)
    {
		$('.modal-body .alert').remove();
		$('.modal-body .feedback_message').html('');
		$('#myModal').modal('show');
    }
});

function send_feedback() {
	if ($('.feedback_message').val() != '') {
		
		$('#preloader').width('562px');
		$('#preloader').height($('#myModal').height());
		var p = $('#myModal').position();
		$('#preloader').css({'left':'50%', 'top': '50%', 'position':'fixed', 'z-index': 1051, 'margin': '-250px 0 0 -280px'});
		$('#preloader').fadeIn('fast');
		var feedback_message = $('.feedback_message').val();
		feedback_message = feedback_message.replace(/(["])/g, "#quot;").replace(/\0/g, "\\0");
		feedback_message = feedback_message.replace(/(['])/g, "#039;").replace(/\0/g, "\\0");
		//feedback_message = escape(feedback_message);
		$.ajax({
		  url: "/feedback",
		  type: "PUT",
		  data: '{ "feedback_message": "'+feedback_message+'", "url": "'+window.location.pathname+'"}',
		  success: function(data) {
			$('#preloader').fadeOut('fast');
			$('.modal-body .alert').remove();
			
			
			
			if (data != null && typeof(data.message) != 'undefined' && typeof(data.code) != 'undefined' && data.code == 200){
				$('.modal-body').append('<span class="alert alert-success">Сообщение успешно отправлено</span>');
				$('.feedback_message').val('');
				setTimeout("$('#myModal .close').click()", 1000);
			} else {
				$('.modal-body').append('<span class="alert hide">Ошибка отправки</span>');
				$('.modal-body .alert').fadeIn();
			}
		  },
		  error: function(data) {
			$('#preloader').fadeOut('fast');
			$('.modal-body .alert').remove();
			$('.modal-body').append('<span class="alert hide">Ошибка отправки</span>');
			$('.modal-body .alert').fadeIn();
			if (data != null && typeof(data.message) != 'undefined')
				$('.modal-body .alert').append(' ('+data.message+')');
		  },
		  dataType: "json"
		});
	} else {
		alert('Введите текст сообщения или описание ошибки');
	}
}
