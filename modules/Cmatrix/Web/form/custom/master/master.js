window.onload = function(){
    let Wp = document.getElementById("wp");
    Wp.style.opacity = 0;
    
    // не понравилось IE
    //setTimeout(() => { Wp.remove(); },400);
    //setTimeout(function(){ Wp.remove(); },400);
    
    // так ему стало хрошо, сука
    setTimeout(function(){ Wp.parentElement.removeChild(Wp); },400);
};