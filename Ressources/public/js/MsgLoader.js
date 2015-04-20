$(document).ready(function() {
     
    var raw_template = $('#test-template').html();
    var template = Handlebars.compile(raw_template);
    $('#msglist').scrollTop($('#msg-container').height());

    setInterval( function() {
        
        $.getJSON("Messages/loader/"+ pseudo + "/" + lastmsg,function(data){
            // alert(JSON.stringify(data));

            $.each(data,function(index,element){
                var html = template(element);
                $('#msg-container').append(html);
                //console.log(element);
            });

            if (data.length > 0) {
                lastmsg = data[data.length-1].idmsg;
                $('#msglist').scrollTop($('#msg-container').height());
            }
            
        });
        
    }, 5000);

   $("#submitmsg").on('click', function(event) {
        event.preventDefault();
        $.post(
            'Messages/sendmsgajax/' + pseudo,
            {
                msg : $("#msg").val(),
            },
            function(data){
                

                $.getJSON("Messages/loader/"+ pseudo + "/" + lastmsg,function(data){
                    // alert(JSON.stringify(data));
                   if (data.length > 0) {
                        lastmsg = data[data.length-1].idmsg;
                    }

                    $.each(data,function(index,element){
                        var html = template(element);
                        $('#msg-container').append(html);
                        console.log(element);
                    });
                });

                // if(data == 'Success'){
                //      $("#resultat").html("<p>Vous avez été connecté avec succès !</p>");
                // }
                // else{
                //      $("#resultat").html("<p>Erreur lors de la connexion...</p>");
                // }
            },
            'json'
        );
        $("#msg").val("");
    });
});