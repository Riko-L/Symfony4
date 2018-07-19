$(function () {


    /**
     * Reformat the value of phone input like "+33(0) 0 00 00 00 00"
     */
    $('#person_phoneNumber').on('focusout', function () {

        if($(this).val().match(/^[0-9]/) ){

            $(this).val( function(id , text) {
                let num = text.replace(/(\d{1})(\d{1})(\d{2})(\d{2})(\d{2})(\d{2})/, '+33'+ '($1) $2 $3 $4 $5 $6');
                return  num
            });
        }
    });

    $('#mailtel').on('focusout', function () {

        if($(this).val().match(/^[0-9]/) ){

            $(this).val( function(id , text) {
                let num = text.replace(/(\d{1})(\d{1})(\d{2})(\d{2})(\d{2})(\d{2})/, '+33'+ '($1) $2 $3 $4 $5 $6');
                return  num
            });
        }
    });



});