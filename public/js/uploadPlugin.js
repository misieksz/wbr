(function($){






var uploadFiles = $('#projects_files')[0]; 
      

function createUploadAlert(textBtn) {
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
            }).appendTo(artAlert);
            
              var statusBar = $('#statusBar');
            if(!statusBar.length)
            {
                var statusBar = $('<output></output>', {
                    'id' : 'statusBar'
                }).text('Postęp wysyłania').appendTo(alertInfo);
            }
            
            var progressBar = $('#progressBar');
            if(!progressBar.length)
            {
                var progressBar = $('<progress></progress>', {
                    'id' : 'progressBar',
                     'value' : 0,
                    'max' : 100
                }).appendTo(alertInfo);
            }
            
          
        } 
       
    }
    return artAlert;
}

function showUploadAlert() {
    var artAlert = createUploadAlert();


    artAlert.css({
        left: $(window).width() / 2 + $(document).scrollLeft() - 140,
        top: $(window).height() / 2 + $(document).scrollTop() - 45
    });
   
    
    artAlert.fadeIn(500);
}

function closeUploadAlert() {

    var artAlert = $('#artAlert');
    artAlert.fadeOut(500);

    var overlay = $('#overlay');
    overlay.fadeOut(3000);
}


function sendFiles(fileInput, uploadFile) {
    
    let formData = new FormData();
    
    for(let i=0; i<uploadFile.files.length; i++){
    formData.append(fileInput, uploadFile.files[i]);
    }   

    let xhr = new XMLHttpRequest();
    xhr.upload.addEventListener("progress", progress, false);
    xhr.addEventListener("load", completeSend, false);
    xhr.addEventListener("error", errorSend, false);
    xhr.addEventListener("abort", abortSend, false);
    xhr.open("POST", "new-project", true);
    xhr.send(formData);
}


function progress(event) {
    
    showOverlay();
    showUploadAlert();
    
    
    var progressBar = $('#progressBar'),
        statusBar = $('#statusBar');
    
    var percent = Math.round((event.loaded/event.total)*100);
    statusBar.text("Wysłano "+convertedBytes(event.loaded)+" z "+convertedBytes(event.total)+" ("+percent+"%)");
    progressBar.val(percent);
}

function completeSend(event) {
    closeUploadAlert();
}

function errorSend(event) {
    $("#status").text("Wysyłanie nie powiodło się!");
}

function abortSend(event) {
    $("#status").text("Wysyłanie zostało przerwane!");
}

function convertedBytes(bytes) {
    var kilobyte = 1024;
    var megabyte = kilobyte*1024;
    var gigabyte = megabyte*1024;
    var terabyte = gigabyte*1024;

    if (bytes>=0 && bytes<kilobyte) return bytes+" B";
    else if(bytes>=kilobyte && bytes<megabyte) return Math.round(bytes/kilobyte)+" kB";
    else if(bytes>=megabyte && bytes<gigabyte) return Math.round(bytes/megabyte)+" MB";
    else if(bytes>=gigabyte && bytes<terabyte) return Math.round(bytes/gigabyte)+" GB";
    else if(bytes>=terabyte) return Math.round(bytes/terabyte)+" TB";
    else return bytes+" B";
}
    
    
/* alert for size of file */

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
    maxFileSize  : 1024,
    fileInput : null,
    uploadFile : null
};

$.fn.uploadPlugin = function (userOptions) {

    var options = $.extend({}, defaults, userOptions);

    return this.each(function () {

        var that = $(this);

        that.on('submit', function (e) {
            e.preventDefault();

    
    
            var sum = 0;
           
            
            for(var i=0; i<options.uploadFile.files.length; i++) {
                
                sum = sum+options.uploadFile.files[i].size;
               
            }
            sum = Math.round(sum/1024);
            console.log(sum);
            if(sum > options.maxFileSize)
            {
                showOverlay(options.fadeInSpeed);
                showAlert(options.textBtn, options.fadeInSpeed);
                return false;
            } else {
                sendFiles(options.inputFile, options.uploadFile);
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