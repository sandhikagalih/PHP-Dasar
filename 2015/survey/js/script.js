var pertanyaan = $('dl'),
	jmlPertanyaan = $('#jmlPertanyaan').val(),
	i = 1;

pertanyaan.hide();
pertanyaan.first().show();

$('.tombol-next').click(function(e) {

	if( i < jmlPertanyaan ) {
		var userid = $('#userid').val(),
			questionid = $('#questionid-' + i).val(),
			optionid = $('input:radio[name=opsi-pertanyaan-' + i + ']:checked').val();

		$(this).parent().next().fadeIn();
		$(this).hide();

		$('html, body').animate({
			scrollTop: $(this).parent().next().offset().top
		}, 500);

		$.ajax({
			url: 'simpan_pertanyaan.php',
			type: 'POST',
			data: {
				userid: userid,
				questionid: questionid,
				optionid: optionid
			},
			// success: function(hasil) {
			// 	$('.hasil').html(hasil);
			// 	$('#modalbox').addClass('active');
			// }
		});
		

		i++;

	} else {
		console.log('selesai');
	}



	e.preventDefault();
});