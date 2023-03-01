$(function(){

    var mediaData = $('.mediadata');
    
    $(window).on('hashchange', function(){

        //goto(window.location.hash);
        //alert(window.location.hash);

    }).trigger('hashchange');


    mediaData.on('click', '.directory', function(e){
        //e.preventDefault();

        //var nextDirectory = $(this).find('a.folder').data('dir');

        //alert(nextDirectory);

        //window.location.hash = encodeURIComponent(nextDirectory);
        //currentPath = nextDirectory;

    });




});