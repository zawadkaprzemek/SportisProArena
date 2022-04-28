/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/global.scss';

// start the Stimulus application
import './bootstrap';

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {
//$('[data-toggle="popover"]').popover();
$('#addClubForm').on('submit',function(e){
    e.preventDefault();
    let btn= $('#add_club_button');
    let url= $(this).prop('action');
    let input=$('#addClubName');
    let name= input.val().trim();
    if(name.length<10)
    {
        input.val(name);
        input.focus();
        return;
    }
    $.ajax({
        method: 'POST',
        url: url,
        data: JSON.stringify({name:name}),
        success: function(data, textStatus, xhr) {
            if(xhr.status==201)
            {
                let select=$('#registration_form_club');
                select.append($('<option>', {
                    value: data.id,
                    text: data.name
                }));
                select.val(data.id).trigger('change');
                select.prop('disabled',true);
                btn.prop('disabled',true);
                $("#addClubModal .close").click()
            }
        },
        complete: function (xhr, textStatus) {
            if(xhr.status!==201)
            {
                console.log(xhr.status,textStatus)
            }
        }
    });
});

$('.buy-btn').on('click',function(){
    let tokens=$(this).data('units');
    $.ajax({
        method: 'POST',
        url: '/payment/add_units',
        data: JSON.stringify({tokens:tokens}),
        success: function(data, textStatus, xhr) {
            window.location.reload();   
        }
    });
});

$('.btn-assign-player').on('click',function(){
    let btn=$(this);
    if(confirm('Na pewno chcesz przypisać tego zawodnika do siebie?'))
    {
        $.ajax({
            method: 'POST',
            url: '/ajax/assign_request',
            data: JSON.stringify({player:$(btn).data('player')}),
            success: function(data, textStatus, xhr) {
                
            }
        });
    }
});



});