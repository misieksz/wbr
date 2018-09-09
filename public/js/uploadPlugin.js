(function($){
    
function createOverlay() {
    var overlay = $('#overlay');

    if (!overlay.length)
    {
        overlay = $('<div></div>', {
            'id': 'overlay',
            'class': 'overlayClass'
        }).appendTo('body');
    }
    return overlay;
}

function createArtAlert(textBtn) {
    var artAlert = $('#artAlert');

    if (!artAlert.length) {
        artAlert = $('<div></div>', {
            'id': 'artAlert',
            'class': 'artAlert'
        }).appendTo('#delegate');

        var alertInfo = $('#artAlertInfo');

        if (!alertInfo.length) {
            alertInfo = $('<div></div>', {
                'id': 'artAlertInfo',
                'class': 'artAlertInfo'
            }).text('Rozmiar plików przekracza 10 MB!')
                    .appendTo(artAlert);
        }
        var artAlertBtn = $('#artAlertBtn');
        if (!artAlertBtn.length) {
            artAlertBtn = $('<div></div>', {
                'class': 'artAlertBtn'
            }).appendTo(alertInfo);
        }
        var cancelBtn = $('#cancelBtn');
        if (!cancelBtn.length) {
            cancelBtn = $('<a></a>', {
                'id': 'cancelBtn',
                'class': 'btn btn-danger'
            }).text(textBtn).appendTo(artAlertBtn);
        }
    }
    return artAlert;
}

function showOverlay(fadeInSpeed) {
    var overlay = createOverlay();

    overlay.css({
        width: $(document).width(),
        height: $(document).height()
    });

    overlay.fadeIn(fadeInSpeed);

}

function showAlert(textBtn, fadeInSpeed) {
    var artAlert = createArtAlert(textBtn);


    artAlert.css({
        left: $(window).width() / 2 + $(document).scrollLeft() - 140,
        top: $(window).height() / 2 + $(document).scrollTop() - 45
    });
    artAlert.fadeIn(fadeInSpeed);
}

function closeAlert(fadeOutSpeed) {

    var artAlert = $('#artAlert');
    artAlert.fadeOut(fadeOutSpeed);

    var overlay = $('#overlay');
    overlay.fadeOut(fadeOutSpeed);
}

var defaults = {
    textBtn: 'Zamknij',
    fadeInSpeed  : 500,
    fadeOutSpeed : 500,
    maxFileSize  : 1024
};

$.fn.fileSizeAlert = function (userOptions) {

    var options = $.extend({}, defaults, userOptions);

    return this.each(function () {

        var that = $(this);

        that.on('submit', function (e) {
            e.preventDefault();

            var files = $('#projects_files');
    
    
            var sum = 0;
           
            
            for(var i=0; i<files[0].files.length; i++) {
                
                sum = sum+files[0].files[i].size;
               
            }
            sum = Math.round(sum/1024);
            console.log(sum);
            if(sum > options.maxFileSize)
            {
                showOverlay(options.fadeInSpeed);
                showAlert(options.textBtn, options.fadeInSpeed);
                return false;
            } else {
                this.submit();
            }

            





        });


        $('#delegate').on('click', '#cancelBtn', function (e) {
            e.preventDefault();
            closeAlert(options.fadeInSpeed);
        });




    });


};

}) (jQuery);




