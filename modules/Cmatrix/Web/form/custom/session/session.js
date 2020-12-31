const _browser = function(){
    document.location.href= App.webpage.path('browser');
};

const _clear = function(){
    document.getElementsByTagName("body")[0].style.cursor = "wait";
    var arr = document.getElementsByClassName("cursor");
    for(var i=0;i<arr.length; i++) arr[i].style.cursor = "wait";
    
    setTimeout(function(){
        document.getElementsByTagName("body")[0].style.cursor = "default";
        for(var i=0;i<arr.length; i++) arr[i].style.cursor = "pointer";
    },500);
    
    localStorage.clear();
};

const _login = function(){
    location.replace(App.webpage.path('login'));
};

const _logout = function(){
    location.replace(App.webpage.path('logout'));
};
