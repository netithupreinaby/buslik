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

function hideAddForm(formType){
    $('.add-field[data-form-type=' + formType + ']').removeClass('open');
    $('.edit-field[data-form-type=' + formType + ']').removeClass('open');
    $('form[data-form-type=' + formType + ']').fadeOut();
}

function showEditForm(formType){
    $('.edit-field[data-form-type=' + formType + ']').addClass('open');
    $('form[data-form-type=' + formType + ']').fadeIn();
}

function hideEditForm(formType){
    $('.edit-field[data-form-type=' + formType + ']').removeClass('open');
    $('form[data-form-type=' + formType + ']').fadeOut();
}

function saveProfileAddress(){

}

function saveProfileFullname(){

}

function fillInFormData(dataId, actionType, formName){

    var postData = {
        action: 'getData',
        actionType: actionType,
        DATA_ID: dataId,
    };

    $.ajax({
        url: ajaxUrl,
        data: postData,
        //dataType: "json",
        method: "POST",
        success: function(data) {
            dataObject = JSON.parse(data);
            console.log(dataObject);
            //$('select[name=STATE] option[value=4]').attr('selected', true);
            if (dataObject.success == true){
                updateFormElementValue(formName, postData.DATA_ID, dataObject);
            }

        }
    });

}

function updateFormElementValue(formId, dataId, data){

    $(formId + ' [name]').each (function(element, index){
        //debugger;

        if ($(this).attr('id') != "isMain") {
            var elementName = $(this).prop('name');
            var elementTag = $(this).prop('tagName');

            if ($(this).attr('type') != "hidden") {
                $(this).val(data.data[dataId][elementName]);
            }

            if (elementTag == "SELECT"){
                $(this).trigger("chosen:updated");
            }
        }
    });
}

function processProfileForm(ajaxUrl, formId){
    //debugger;
    var formData = getFormObj(formId);
    $.ajax({
        url: ajaxUrl,
        data: formData,
        dataType: "json",
        method: "POST",
        success: function(data) {
            console.log(data);
        }
    });
}

function getFormObj(formId) {
    var formObject = {};
    var inputs = $('#'+formId).serializeArray();
    $.each(inputs, function (i, input) {
        formObject[input.name] = input.value;
    });
    return formObject;
}


/*************************** End functions *****************************/

$(document).ready(function(){

    $('select[data-form-type]').on('change', function(){

        var formType = $(this).data('form-type');
        hideEditForm(formType);
    });

    $('.add-field').on('click', function(e) {
        e.preventDefault();
        var formType = $(this).data('form-type');
        $('form[data-form-type=' + formType + '] [name]').val('');
        $('form[data-form-type=' + formType + '] select').trigger("chosen:updated");
        $('.edit-field[data-form-type=' + formType + ']').removeClass('open');
        if ($(this).hasClass('open')){
            $(this).removeClass('open');
            $('form[data-form-type=' + formType + ']').fadeOut();
        } else {
            $(this).addClass('open');
            var formId = "#" + $('form[data-form-type=' + formType + ']').attr('id');
            $(formId + ' [name=recordId]').val('');
        }

    });

    $('.edit-field').on('click', function(e) {
//debugger;
        e.preventDefault();

        var formType = $(this).data('form-type');
        $('.edit-field[data-form-type=' + formType + ']').removeClass('open');
        if ($(this).hasClass('open')){
            hideEditForm(formType);
        } else {
            var dataId = $('select[data-form-type=' + formType + ']').val();
            var formId = "#" + $('form[data-form-type=' + formType + ']').attr('id');
            fillInFormData(dataId, formType, formId);
            showEditForm(formType);
            $(formId + ' [name=recordId]').val(dataId);
        }

    });

    // Validation
    $('#addressForm').validationEngine();
    $('#fioForm').validationEngine();
    $('#phoneForm').validationEngine();

    // Buttons handlers and ajax
    $('#addressForm input[type=submit].send').on('click', function(e){
        e.preventDefault();
        processProfileForm(ajaxUrl, 'addressForm');
    });

    $('#addressForm input[type=submit].reset').on('click', function(e){
        e.preventDefault();
        hideAddForm('addAddress');
    });

    $('#fullnameForm input[type=submit].send').on('click', function(e){
        e.preventDefault();
        alert("fullnameForm");
    });

    $('#fullnameForm input[type=submit].reset').on('click', function(e){
        e.preventDefault();
        hideAddForm('addFullname');
    });

    $('#phoneForm input[type=submit].send').on('click', function(e){
        e.preventDefault();
        alert("phoneForm");
    });

    $('#phoneForm input[type=submit].reset').on('click', function(e){
        e.preventDefault();
        hideAddForm('addPhone');
    });

    setTimeout(function(){
        ajaxUrl = BX.message('COMPONENT_TEMPLATE_PATH') + '/ajax.php';
    },1000);

});