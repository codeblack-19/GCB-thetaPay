import $ from 'jquery';

export default function AppbarOperation(){
    var $window = $(window);
    var appbar = document.getElementById('AppBar');
    $window.on('scroll', function(){
        if($window.scrollTop() > 50){
            appbar.style.backdropFilter = 'blur(10px)';
            appbar.style.backgroundColor = 'rgba(252, 176, 64, 0.78)';
        }else{
            if($window.width() > 1024){
                appbar.style.backdropFilter = 'blur(0px)';
                appbar.style.backgroundColor = 'rgba(255, 255, 255, 0)';
            }
        }
    })
}