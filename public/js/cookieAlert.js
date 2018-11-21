(function($, window, document, undefined){
    

function setToLs(key, val) {
    
    if(!"localStorage" in window)
    {
        return;
    }
    
    window.localStorage.setItem(key, val);
}

function getFromLs(key) {
    
    if(!"localStorage" in window)
    {
        return;
    }
    
    return window.localStorage.getItem(key);
}

var defaults = {
    class: 'cookieAlert',
    message: 'Serwis korzysta z plików cookies w celu usprawnienia działania strony.',
    animSpeed: 500,
    btnClass: 'btn btn-primary',
    btnText: 'Rozumiem'
};

$.fn.cookieAlert = function(userOptions) {
    
    if(getFromLs('cookiesAccepted') === "1") {
        return this;
    }
    

    var options = $.extend({}, defaults, userOptions);
        
    
    var cookieAlert = $('<div></div>', {
        id: 'cookieAlert',
        class: options.class,
        text: options.message
    }).hide();   
    
    var button = $('<button></button', {
        id: 'closeBtn',
        class: options.btnClass,
        text: options.btnText
    }).appendTo(cookieAlert);
    
    button.on('click', function(){
        setToLs('cookiesAccepted', 1);
        cookieAlert.slideUp(options.animSpeed, function(){
            cookieAlert.remove();
        });
        
    });
    
    this.append(cookieAlert);
    
    cookieAlert.slideDown(options.animSpeed);
    
   
    
    return this;
    

    
    
};
})(jQuery, window, document);