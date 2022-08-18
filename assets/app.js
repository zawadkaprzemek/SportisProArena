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
import 'datatables';
// start the Stimulus application
import './bootstrap';
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
//require('bootstrap');
window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.min.js');

//require('bootstrap-select');
// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

jQuery(function(){
//$('[data-toggle="popover"]').popover();

//$('select.selectpicker').selectpicker();
const trainingDateCheckbox='<div class="form-check"><input class="form-check-input" name="training_dates[]" type="checkbox" value="__VALUE__" id="trainingDate__COUNT__">'+
'<label class="form-check-label" for="trainingDate__COUNT__">__TEXT__</label></div>';

const ACCORDION_PROTOTYPE="<div class=\"accordion accordion-flush\" id=\"__ID__Accordion\"></div>";
const ACCORDION_ITEM="<div id=\"accordion-item__TARGET____X__\" class=\"accordion-item\">\n" +
    "    <h2 class=\"accordion-header\" id=\"flush-series__X__\">\n" +
    "        <button class=\"accordion-button collapsed\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#flush-collapse__TARGET____X__\" aria-expanded=\"false\"\n" +
    "                aria-controls=\"flush-collapse__TARGET____X__\">\n" +
    "            __HEADER__ __Y__\n" +
    "        </button>\n" +
    "    </h2>\n" +
    "    <div id=\"flush-collapse__TARGET____X__\" class=\"accordion-collapse collapse\" aria-labelledby=\"flush-__TARGET____X__\" data-bs-parent=\"__ID__Accordion\">\n" +
    "        <div class=\"accordion-body\">\n" +
    "            __CONTENT__\n" +
    "        </div>\n" +
    "    </div>\n" +
    "</div>"

const paginationItem='<a href="#" data-page="__PAGE__">__PAGE__</a>'


$('input[name="registration_form[userType]"]').on('change',function(){
    if($(this).val()===1)
    {
        $('#registerPage').removeClass('manager').addClass('player');
        $('.player-field').removeClass('d-none').find('input,select').prop('disabled',false);
        $('.manager-field').addClass('d-none').find('input,select').prop('disabled',true);
    }else if($(this).val()===2)
    {
        $('#registerPage').addClass('manager').removeClass('player');
        $('.manager-field').removeClass('d-none').find('input,select').prop('disabled',false);
        $('.player-field').addClass('d-none').find('input,select').prop('disabled',true);
    }
})

$('#addClubForm').on('submit',function(e){
    e.preventDefault();
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
            if(xhr.status===201)
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
            const infoModal = new bootstrap.Modal('#infoModal', []);
            $('#infoModal .modal-body').html('<p>'+data.message+'</p>');
            infoModal.show();
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
            if(data.status==="success")
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

$('.training-date-td').on('click',function(){
    let url=$('#calendar table').data('url'),
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
    let day=$('#training_day').val();
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
                    $('#calendar td[data-date="'+day+'"]').find('h3').addClass('reserved_day');
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
        if($(this).val()==='-1'){
            $('#add_club_button').trigger('click');
        }
    });
}

$('.menu-section .section-header').on('click',function(){
    $(this).parent().toggleClass('open');
});

$('.hamburger-menu').on('click',function(){
    let target=$(this).data('target');
    $(target).toggleClass('show');
});

if($('table.dataTable').length>0)
{
var th_count=$('table.dataTable th').length;
var table = $('table.dataTable').DataTable({
    info: false,
    "lengthMenu": [ [10], [10] ],
    columnDefs: [
        { orderable: false, targets: th_count-1 }
      ]
});

$("#searchBox").on( 'keyup', function () {
    table.search( this.value ).draw();
    renderPagination(table.page.info());
} );

}


function renderPagination(info)
{
    let pagination=$('.pagination .pages');

    $(pagination).find('a:not(.btn)').remove();
    let links='';
    for(var i=1;i<=info.pages;i++)
    {
        links+=paginationItem.replaceAll('__PAGE__',i);
    }

    $(links).insertAfter('.pagination .prev-page');
    let page=info.page+1;
    $('.pagination a[data-page="'+page+'"]').addClass('current');
    $('.pagination .prev-page').addClass('d-none');
    $('#currentPage').text(page);
    $('#totalPages').text(info.pages);
    if(page===info.pages){
        $('.pagination .next-page').addClass('d-none');
    }else{
        $('.pagination .next-page').removeClass('d-none');
    }

    if(info.pages===0)
    {
        $('.pagination').addClass('d-none');
    }else{
        $('.pagination').removeClass('d-none');
    }
}


$('body').on('click','.pagination a',function(e){
    e.preventDefault();
    var page=$(this).data('page');
    $('.pagination .current')
    if(page==='next'||page==='previous')
    {
        $('.paginate_button.'+page).trigger('click');
    }else{
        $('span .paginate_button').eq((page-1)).trigger('click');
    }
    let info=table.page.info();
    let current=info.page+1;
    $('#currentPage').text(current);
    $('.pagination a').removeClass('current');
    $('.pagination a[data-page="'+current+'"]').addClass('current');
    $('table.dataTable').data('current-page',current);
    let total=info.pages;
    if(current===1)
    {
        $('.pagination .prev-page').addClass('d-none');
    }else{
        $('.pagination .prev-page').removeClass('d-none');
    }

    if(current===total){
        $('.pagination .next-page').addClass('d-none');
    }else{
        $('.pagination .next-page').removeClass('d-none');
    }
});

$('#trainingUnitForm input[name="training_unit[test]"][value="test"]').prop('disabled',true);
$('#trainingUnitForm input[name="training_unit[trainingType]"][value="pair"]').prop('disabled',true);


let trainingUnitForm=$('#trainingUnitForm');
if(trainingUnitForm.length>0)
{
    var fieldNames=[];

    let ageCat=$('[name="training_unit[ageCategory]"]:checked').val();
    //console.log(ageCat);
    if(ageCat==="youth")
    {
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:gt(-6)').hide();
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:gt(-6) input').prop('disabled',true).prop('checked',false);
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:lt(-5)').show();
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:lt(-5) input').prop('disabled',false);
    }
    if(ageCat==="open")
    {
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:gt(-6)').show();
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:gt(-6) input').prop('disabled',false);
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:lt(-5)').hide();
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:lt(-5) input').prop('checked',false).prop('disabled',true);
    }
    checkReadyToSubmit('#trainingUnitForm');
    $("#trainingUnitForm input").each(function(i,input){
        var name=$(input).prop('name');
        
        if(name!=='training_unit[_token]'){
            fieldNames.push($(input).prop('name'));
        }
        
    });
    fieldNames=fieldNames.filter(onlyUnique);

    $('body').on('change','#trainingUnitForm input',function(){
        watchTrainingUnitFormStep1($(this));
    });
    let p_m_Inputs=$('.plus-minus-input');
    $.each(p_m_Inputs,function (i,elem){
       insertPlusMinusButtons(elem);
    });
}

function insertPlusMinusButtons(elem)
{
    let btnMinus="<span class=\"input-group-text\">\n" +
        "<button type=\"button\" class=\"btn btn-secondary btn-change-count btn-minus\" data-target=\"#__ID__\">-</button>\n" +
        "</span>";
    let btnPlus="<span class=\"input-group-text\">\n" +
        "<button type=\"button\" class=\"btn btn-secondary btn-change-count btn-plus\" data-target=\"#__ID__\">+</button>\n" +
        "</span>";

    btnPlus=btnPlus.replaceAll('__ID__',$(elem).attr('id'));
    btnMinus=btnMinus.replaceAll('__ID__',$(elem).attr('id'));
    $(btnMinus).insertBefore($(elem));
    $(btnPlus).insertAfter($(elem));
    $(elem).parent().find('label').removeClass('input-group-text')
}


function onlyUnique(value, index, self) {
  return self.indexOf(value) === index;
}

function watchTrainingUnitFormStep1(input)
{
    let name =$(input).attr('name');
    let target=$(input).parent().parent().data('show');
    if(target==='#training_unit_trainingType')
    {
        $('.step-2').removeClass('d-none');
    }

    if(target==='#training_unit_seriesCount')
    {
        $('.step-3').removeClass('d-none');
    }
    
    let ageCat=$('[name="training_unit[ageCategory]"]:checked').val();
    if(ageCat==="youth")
    {
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:gt(-6)').hide();
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:gt(-6) input').prop('disabled',true).prop('checked',false);
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:lt(-5)').show();
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:lt(-5) input').prop('disabled',false);
    }
    if(ageCat==="open")
    {
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:gt(-6)').show();
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:gt(-6) input').prop('disabled',false);
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:lt(-5)').hide();
        $('#training_unit_trainingSubGroupsAgeCategories .form-check:lt(-5) input').prop('checked',false).prop('disabled',true);
    }
    if($('#training_unit_trainingSubGroupsAgeCategories .form-check input:checked').length===0)
    {
        $('#training_unit_trainingSubGroupsLevels').parent().addClass('d-none');
    }


    let formArray=$('#trainingUnitForm').serializeArray();
    let find=false;

    $(formArray).each(function(i,elem){
        if(elem.name===name)
        {
            find=true;
        }
    });
    checkReadyToSubmit('#trainingUnitForm')
    //$('#trainingUnitForm button[type="submit"]').prop('disabled',Object.keys(data).length<fieldNames.length)
    if(find)
    {
        $(target).parent().removeClass('d-none');
    }else{
        $(target).parent().addClass('d-none');

        if(target==='#training_unit_seriesCount')
        {
            $('.step-3').addClass('d-none');
        }
    }
}

function checkReadyToSubmit(form)
{
    let formArray=$(form).serializeArray();
    let data = getFormData(JSON.parse(JSON.stringify( formArray )));
    $(form+' button[type="submit"]').prop('disabled',Object.keys(data).length<fieldNames.length)
}

function getFormData(json)
{
    let obj = [];
    json.forEach(function (item){
        if(item.name!=="training_unit[_token]")
        {
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
        }
    })
    return Object.assign({},obj);
}

$('body').on('click','.btn-change-count',function(){
    let target= $(this).data('target');
    let value=parseInt($(target).val());
    let itemsCount=$('#accordionSeries > .accordion-item').length;
    if($(this).hasClass('btn-minus')&& value>$(target).attr('min'))
    {
        value--;
    }
    if($(this).hasClass('btn-plus'))
    {
        value++;
        let accId,
            rangeInputs,
            p_m_Inputs;
        if(target==="#training_unit_seriesCount")
        {
            let seriesForm=$('#accordionSeries').attr('data-prototype');
            seriesForm=seriesForm.replaceAll(/__name__/g,itemsCount);
            let itemProt=$('#accordionSeries').attr('data-accordion-prototype');
            itemProt=itemProt.replaceAll('__X__',(itemsCount+1)).replaceAll('__FORM__',seriesForm);
            $(itemProt).appendTo($('#accordionSeries'));
            accId=$('#'+$(itemProt).attr('id'));

            p_m_Inputs=accId.find('.plus-minus-input');
            rangeInputs=accId.find('input[type="range"]');
        }else{
            let elemTarget=target.replace('seriesVolume','trainingUnitThrowConfigs');
            let accordion=$(elemTarget).find('.accordion');
            if(accordion.length===0)
            {
                createAccordion(elemTarget);
                accordion=$(elemTarget).find('.accordion');
            }
            let throwsCount=accordion.find('.accordion-item').length;
            let elem=$(elemTarget).attr('data-prototype');
            let accItem=ACCORDION_ITEM.replaceAll('__ID__',elemTarget.replace('#','')).replaceAll('__TARGET__',elemTarget.replace('#',''))
                .replaceAll('__X__',throwsCount).replaceAll('__HEADER__','Konfiguruj wyrzut').replaceAll('__Y__',(throwsCount+1))
            elem=elem.replaceAll(/__name__/g,throwsCount).replaceAll(/__t__/g,throwsCount).replaceAll('__X__',(throwsCount+1));
            accItem=accItem.replaceAll('__CONTENT__',elem);
            $(accItem).appendTo($(accordion));
            accId=$('#'+$(accItem).attr('id'));
            p_m_Inputs=accId.find('.plus-minus-input');
            rangeInputs=accId.find('input[type="range"]');
        }
        $.each(p_m_Inputs,function (i,elem){
            insertPlusMinusButtons(elem);
        });
        $.each(rangeInputs,function (i,input){
            addRangeBubble(input)
        });
    }
    $(target).val(value);
})

    function createAccordion(target)
    {
        let accordion=ACCORDION_PROTOTYPE.replaceAll('__ID__',target);
        $(accordion).appendTo(target);
    }

function addRangeBubble(input){
    let output='<output class="bubble" id="'+$(input).attr('id')+'_bubble"></output>';
    $(output).insertAfter(input);
    let bubble='#'+$(input).attr('id')+'_bubble'
    $(input).attr('data-bubble',bubble);
    $(input).on("input", function () {
        setBubble($(input), $(bubble));
    });
    setBubble($(input), $(bubble));
}

let rangeInputs=$('input[type="range"]');
$.each(rangeInputs,function(i,input){
    addRangeBubble(input);
});


$.each($("body .range-wrap"),function (i,wrap) {
  const range = $(wrap).find(".range");
  const bubble = range.attr("data-bubble");
  range.on("input", function () {
    setBubble(range, $(bubble));
  });
  setBubble(range, $(bubble));
});


function setBubble(range, bubble) {
  const val = range.val();
  const min = range.attr('min') ? range.attr('min') : 0;
  const max = range.attr('max') ? range.attr('max') : 100;
  const newVal = Number(((val - min) * 100) / (max - min));

  bubble.html(val);

  // Sorta magic numbers based on size of the native UI thumb
    if(bubble.parent().hasClass('range-vertical'))
    {
        let hP=newVal*range.height()/100;
        bubble.css("bottom",`calc(${hP}px - (${newVal * 0.15}px))`);
    }else{
        bubble.css("left",`calc(${newVal}% + (${8 - newVal * 0.15}px))`);
    }
}

$('body').on('change','.tasks-what input',function (){
    let val=$(this).val();
    let target=$(this).parent().parent().data('show');
    showTasksHow(val,target)

});

function showTasksHow(value,target)
{
    let hideVals=[];
    switch (value){
        case 'take-ball':
            hideVals=['head']
            break;
        case 'first-touch':
            hideVals=['chest'];
            break;
        case 'free-choice':
            hideVals=['head','chest'];
            break;
        default:break;
    }
    $(target).find('input').prop('disabled',false);
    $(target).find('.form-check').show();
    $.each(hideVals,function (i,val){
        let elem =$(target).find('input[value="'+val+'"]');
        elem.prop('disabled',true).prop('checked',false);
        elem.parent().hide();
    });
}
});