$(document).ready(function(){

    $("#regForm").validationEngine();

    $('#additionalInfoSwitcher').on('click', function(){
        $(this).toggleClass('opened');

        if ($(this).hasClass('opened')){
            $('[name="REGISTER[ADDITIONAL_INFO]"]').val('Y');
        } else {
            $('[name="REGISTER[ADDITIONAL_INFO]"]').val('');
        }
    });

    $('[name="REGISTER[UF_PERSONAL_CHILDREN_COUNT]"]').on('change', function(){
        var count = $(this).val();
        getChildFields(this, count);
    });

    function getChildFields (element, count) {
        $('.child-data').remove();
        for (var i = count-1; i >= 0; i--){
            getChildFieldsHtml('.children-count', i);
        }
    }

    function getChildFieldsHtml (element, index) {
        var html = '<div class="col-md-6 col-sm-6 col-xs-12 child-data clear-both">' +
            '<div class="form-group">' +
                '<label class="required-field" for="UF_PERSONAL_CHILD_NAME_' + index + '">Имя</label>' +
                '<input class="form-control" id="UF_PERSONAL_CHILD_NAME_' + index + '" type="text" name="REGISTER[UF_PERSONAL_CHILD_NAME][' + index + ']">' +
            '</div>' +
            '<div class="form-group">' +
                '<label class="required-field" for="UF_PERSONAL_CHILD_BIRTHDAY_' + index + '">Дата рождения</label>' +
                '<div class="input-group date datetimepicker">' +
                    '<span class="input-group-addon">' +
                        '<span class="glyphicon glyphicon-calendar"></span>' +
                    '</span>' +
                    '<input type="text" class="form-control" name="REGISTER[UF_PERSONAL_CHILD_BIRTHDAY][' + index + ']">' +
                '</div>' +
            '</div>' +
        '</div>';
        html += '<div class="col-md-6 col-sm-6 col-xs-12 child-data">' +
                '<div class="form-group">' +
                    '<label class="required-field" for="UF_CHILD_GENDER_' + index + '">Пол</label>' +
                    '<ul class="sex-radio nav-pills clearfix">' +
                        '<li>' +
                            '<input type="radio" id="UF_CHILD_GENDER_M_' + index + '" checked name="REGISTER[UF_CHILD_GENDER][' + index + ']" value="M">' +
                           '<label for="UF_CHILD_GENDER_M_' + index + '">муж.</label>' +
                        '</li>' +
                        '<li>' +
                            '<input type="radio" id="UF_CHILD_GENDER_F_' + index + '" name="REGISTER[UF_CHILD_GENDER][' + index + ']" value="F">' +
                            '<label for="UF_CHILD_GENDER_F_' + index + '">жен.</label>' +
                        '</li>' +
                    '</ul>' +
                '</div>' +
                '<div class="form-group">' +
                    '<label class="required-field" for="asexample978">Родственная связь</label>' +
                    '<select class="chosen-select" tabindex="2"  name="REGISTER[UF_PERSONAL_RELATIVES][' + index + ']">' +
                        '<option value="1">Минск</option>' +
                        '<option value="2">НеМинск</option>' +
                        '<option value="3">Минск</option>' +
                        '<option value="4">НеМинск</option>' +
                    '</select>' +
                '</div>' +
            '</div>';
        $(element).after(html);

        $('.datetimepicker').datetimepicker({
            locale: 'ru',
            format: 'D MMMM YYYY',
            keepOpen: true,
            widgetPositioning: {
                vertical: 'bottom'
            }
        });

    }


});