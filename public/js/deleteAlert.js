 function createOverlay(){
        var overlay = $('#overlay');
        
        if(!overlay.length)
        {
           overlay =  $('<div></div>', {
                'id' : 'overlay',
                'class' : 'overlayClass'
            }).appendTo('body');
        }
        return overlay;
    }
    
  
             
    function showOverlay(){
                 var overlay = createOverlay();
                 
                 overlay.css({
                     width: $(document).width(),
                     height: $(document).height()
                 });
                 
                 overlay.fadeIn(500);
                 
             }
             
             function showAlert(){
                 var artAlert = $('#artAlert');
                 
                 
                 artAlert.css({
                     left: $(window).width() /2 + $(document).scrollLeft() - 140,
                     top: $(window).height()/2 + $(document).scrollTop() - 45
                 });
                 artAlert.fadeIn(500);
             }
             
             function closeAlert(){
                   
                     var artAlert = $('#artAlert');
                     artAlert.fadeOut(500);
                     
                     var overlay = $('#overlay');
                     overlay.fadeOut(500);
             }
       

$.fn.deleteAlert = function(){
    
    return this.each(function(){
                
                var that = $(this);
                   
                 that.on('click', function(e){
                     e.preventDefault();
                     
                     var removeUrl = that.attr('href');
                     
                     showOverlay();
                     showAlert();
                     
                     $('#alertAgree').one('click', function(e){
                         
                         e.preventDefault();
                         
                         
                        $.getJSON(removeUrl, function(json){
                            
                            if('ok' === json.status) that.closest('tr').remove();
                            
                        });
                        
                        closeAlert();
                          
                     });
                    
                      
         
                 });
    });

};

$('#alertCancel').on('click', function(e){
          e.preventDefault();
          closeAlert();
      });
      
      
                  
           

