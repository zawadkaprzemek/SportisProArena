/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/global.scss';

const $ = require('jquery');
// start the Stimulus application
import './bootstrap';
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.js');

//require('bootstrap-select');
// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {
//$('[data-toggle="popover"]').popover();\

//$('select.selectpicker').selectpicker();
const trainingDateCheckbox='<div class="form-check"><input class="form-check-input" name="training_dates[]" type="checkbox" value="__VALUE__" id="trainingDate__COUNT__">'+
'<label class="form-check-label" for="trainingDate__COUNT__">__TEXT__</label></div>';




$('input[name="registration_form[userType]"]').on('change',function(){
    if($(this).val()==1)
    {
        $('#registerPage').removeClass('manager').addClass('player');
        $('.player-field').removeClass('d-none').find('input,select').prop('disabled',false);
        $('.manager-field').addClass('d-none').find('input,select').prop('disabled',true);
    }else if($(this).val()==2)
    {
        $('#registerPage').addClass('manager').removeClass('player');
        $('.manager-field').removeClass('d-none').find('input,select').prop('disabled',false);
        $('.player-field').addClass('d-none').find('input,select').prop('disabled',true);
    }
})

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
                select.find("option[value='-1']").remove();
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
          }).done(function(data) {
                alert(data.message);
          });
    }
});


$('.notification-answers button').on('click',function(){
    let notification=$(this).parent().data('referenceid'),
        answer= $(this).data('answer'),
        btn=$(this);
    $.ajax({
        method: 'POST',
        url: '/ajax/answer_request',
        data: JSON.stringify({request:notification,answer:answer}),    
      }).done(function(data) {
            if(data.status=="success")
            {
                $(btn).parent().parent().remove();
            }
      });
})

$('.notification_row').on('click',function(){
    let row=$(this);
    let notification=$(this).data('notification');
    if($(this).hasClass('fw-bold'))
    {
        $.ajax({
            method: 'GET',
            url: '/ajax/read_notification/'+notification,
          }).done(function(data) {
                $(row).removeClass('fw-bold');
          });
    }
    $(this).find('.col-12').toggleClass('d-none');
})

$('.training-date-btn').on('click',function(){
    let btn=$(this),
    url=$('#calendar table').data('url'),
    date=$(this).data('date');
    $('#training_day').val(date);
    $('#trainingDatesModal .alert').addClass('d-none').removeClass('alert-danger').removeClass('alert-scucess').text('');
    $.ajax({
        method: 'GET',
        url: url+'?date='+date,
      }).done(function(data) {
            renderTrainingDatesCheckboxes(data.slots);
      });
});


function renderTrainingDatesCheckboxes(data)
{
    let html='',
    count=1;
    $.each(data,function(k,v){
        html+=trainingDateCheckbox.replace('__VALUE__',k).replace('__TEXT__',v).replaceAll('__COUNT__',count);
        count++;
    });
    $('#reserveTrainingForm .modal-body .col-12').html(html);
}

$('#reserveTrainingForm').on('submit',function(e){
    e.preventDefault();
    let url=$(this).prop('action');
    clearAlert($('#trainingDatesModal .alert'));
    let data = getFormData(JSON.parse(JSON.stringify( $(this).serializeArray() )));
    if(confirm('Na pewno chcesz zarezerwować te terminy?'))
    {
        if(typeof data.training_dates ==="undefined")
        {
            showAlert($('#trainingDatesModal .alert'),'alert-warning','Nie wybrano żadnego terminu!');
            return;
        }
        $.ajax({
            method: 'POST',
            url: url,
            data: JSON.stringify(data),    
          }).done(function(data) {
                showAlert($('#trainingDatesModal .alert'),'alert-'+data.status,data.message);
                if(data.status!=='danger')
                {
                    updateUnitsCount(data.units);
                    removeReserved(data.sessions);
                    removeChecked();
                }
          });
    }
})

$("#trainingDatesModal .close").on('click',function(){
    clearAlert($('#trainingDatesModal .alert'));
});


function updateUnitsCount(count)
{
    $('#trainingUnitsCount').text(count);
}

function clearAlert(alertElem)
{
    alertElem.addClass('d-none').removeClass('alert-danger').removeClass('alert-scucess').removeClass('alert-warning').text('');
}

function showAlert(alertElem,cls,message)
{
    alertElem.addClass(cls).text(message).removeClass('d-none');
}

function getFormData(json)
{
    let obj = [];
    json.forEach(function (item){
        if(item.name.slice(-2)==="[]")
        {
            let len = (!(item.name.slice(0, -2) in obj) ? 0 : obj[item.name.slice(0, -2)].length);
            if(len===0)
            {
                obj[item.name.slice(0,-2)]=[];
            }
            obj[item.name.slice(0,-2)][len]=item.value;
        }else{
            obj[item.name]=item.value;
        }
    })
    return Object.assign({},obj);
}

function removeReserved(items)
{
    $.each(items,function(key,val){
        $('#reserveTrainingForm').find("input[value='"+val+"']").parent().remove();
    });
}


function removeChecked()
{
    let items = $('#reserveTrainingForm').find("input:checked");
    console.log(items);
    $.each(items,function(){
        $(this).parent().remove();
    });
}

let registerForm=$('#registerForm');

if(registerForm.length>0)
{
    let select=$('#registration_form_club');
    select.prepend($('<option>', {
        value: '-1',
        text: 'Dodaj mój klub...'
    }));

    select.on('change',function(){
        if($(this).val()=='-1'){
            $('#add_club_button').trigger('click');
        }
    });
}


});