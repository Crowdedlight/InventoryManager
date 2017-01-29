$(document).ready(function () {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#found_users').on('click', '.user_modal', function(e){
        e.preventDefault();

        $('#user_info > .modal-dialog > .modal-content').load($(this).attr('href'));
        $('#user_info').modal('show');
    });

    $('#search_user' ).on('blur', function(){
        if ( $( this ).val().length < 3 ) {
            $( '#found_users' ).empty();
        }
    });

    $('#search_user').on('keydown', function() {

        $( '#found_users' ).empty();

        if ( $( this ).val().length >= 3 ) {
            $.post( "/admin/api/users", {'data': $( this ).val() }, function( data ) {
                data = JSON.parse( data );

                for ( var index in data ) {
                    var entry = $( '#found_users' ).append( '<li class="list-group-item found_item">'
                        + '<a class="user_modal" href="/admin/user/info/' + data[ index ][ 'id' ] + '">' + data[ index ][ 'name' ] + '</a>' +
                        '</li>' );
                }
            } );
        }

    });

});