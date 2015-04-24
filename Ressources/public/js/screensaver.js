$(document).ready(function() {
    crazymode = false;
    timescreensaver = 50000;
    movementTimer = setTimeout(function()
        {
            if($('#crazymode').length == 0) {
                crazymode = true;
                var drawing = document.createElement('div');
                drawing.id = "screensaver";
                document.body.appendChild(drawing);
            }
        }, timescreensaver);
    $(window).mousemove(function(e)
    {
        
        if(crazymode == true) {
            var drawing = document.getElementById('screensaver');
            document.body.removeChild(drawing);
            crazymode = false;
        }
        clearTimeout(movementTimer);
        movementTimer = setTimeout(function()
        {
            if($('#crazymode').length == 0) {
                crazymode = true;
                var drawing = document.createElement('div');
                drawing.id = "screensaver";
                document.body.appendChild(drawing);
            }
        }, timescreensaver);
    });
});