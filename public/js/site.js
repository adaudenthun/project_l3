$( document ).ready(function() {

    $( "input" ).focus(function() {
        var input = $( this ).attr( 'id' );
        $( "label[for='"+input+"']" ).addClass( "active" );
    });
    $( "input" ).focusout(function() {
        var input = $( this ).attr( 'id' );
        if($( this ).val() == "") {
            $("label[for='" + input + "']").removeClass("active");
        }
    });

});