function showDataSavedPopup(){
    $(document).ready(function(){
        $('body').addClass('lock');
        $('body').prepend('<div class="shim"></div>');
        $('.shim').fadeIn(250);
        $('#data-saved').fadeIn(250);

        $('.data-saved .close-popup').on('click', function (e) {
            e.preventDefault();
            $('.shim').fadeOut(250);
            $('.proposal-form').fadeOut(250);
            $('body').removeClass('lock');
            $('.data-saved').hide();
        });

        setTimeout(function(){
            $('a.close-popup').trigger('click');
        }, 3000);
    });
}

$(document).ready(function(){
    $("#dataForm").validationEngine();
});

