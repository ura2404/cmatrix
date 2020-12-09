window.onload = function(){
    let Wp = document.getElementById("wp");
    Wp.style.opacity = 0;
    setTimeout(() => { Wp.remove(); },400);
};