jQuery(document).ready( function($) {
	$("input[name='sishen_test_option[color]']").blur( function() {
		$.ajax({
			type: "POST",
			data: "color=" + $(this).val() + "&action=color_check_action",
			url: ajax_object.ajax_url,
			beforeSend: function() {
				$('#error_color').html('校验中...');
			},
			success: function( $data ) {
				if( $data == 'ok'){
				    $('#error_color').html('输入正确');
				} else {
				    $('#error_color').html('颜色不能为空！');
				}
			}
		});
	});
});


jQuery(document).ready( function($) {
	$(".site-description").click( function() {
		$.ajax({
			type: "POST",
			data: "description=" + $(this).text() + "&action=hcsem_description",
			url: ajax_object.ajax_url,
			success : function( $data ) {

				if( $data != "0" ) {
					$(".site-description").text( $data );
				}
			}
		});
	});
});

alert('11111');