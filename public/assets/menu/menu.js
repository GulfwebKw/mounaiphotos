window.onload=function(){
var slidebar_width  = 290; //slidebar width + padding size
var slide_bar       = $(".side-menu-wrapper"); //slidebar
var slide_open_btn  = $(".slide-menu-open"); //slidebar close btn
var slide_close_btn = $(".menu-close"); //slidebar close btn
var overlay         = $(".side-menu-overlay"); //slidebar close btn

slide_open_btn.click(function(e){
    e.preventDefault();
    slide_bar.css( {"right": "0px"}); //change to "right" for right positioned menu
    overlay.css({"opacity":"1", "width":"100%"});
});
slide_close_btn.click(function(e){
    e.preventDefault();
    slide_bar.css({"right": "-"+ slidebar_width + "px"}); //change to "right" for right positioned menu
    overlay.css({"opacity":"0", "width":"0"});  
});
}//]]>