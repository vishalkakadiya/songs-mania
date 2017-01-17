(function( $, wp ) {
    'use strict';

    // Nonce
    var nonce = smSongLikeNonce;

    window.songsObj = {

        // function when like button clicked
        likeSong: function ( postId ) {
            var params = {
                sm_nonce: smSongLikeNonce,
                post_id: postId
            };

            // Ajax request to create account
            var request = wp.ajax.post( 'sm_like_song', params );

            // Check ajax response
            request.done( function( data ) {
                // data.likes_count
                $( '.sm-likes-' + postId ).text( data.likes_count );
            }, 'json' );

            request.fail( function( data ) {
                console.log('===  Error ====');
                console.log(data);
            } );

            request.always( function( data ) {
                console.log('===  Always ====');
                console.log(data);
            } );
        },
    };

    // Like button call event.
    $( '.sm-like' ).on( 'click', function () {
        var postId = $( this ).data( "id" );
        songsObj.likeSong( postId );
    } );

})( jQuery, window.wp );
