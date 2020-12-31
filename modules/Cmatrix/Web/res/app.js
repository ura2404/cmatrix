const App = {
    
};

App.webpage = {
    path : function(url){
        console.log(_cmpp(url));
        return _cmpp(url);
    },
};


App.browser = {
    /**
     * Функция проверки браузера
     *  -flex
     *  -grid
     */
    check : function(){
        $_css = function(){
            if(typeof CSS == 'undefined') return false;
            
            var f = true;
            f = f && CSS.supports("display", "flex");
            f = f && CSS.supports("display", "grid");
            return f;
        };
        
        $_js = function(){
            return true;
        };
        
        return $_css() && $_js();
    }
};