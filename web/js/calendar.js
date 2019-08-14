/*Календарь на главной странице*/

function Calendar2(id, year, month) {
var Dlast = new Date(year,month+1,0).getDate(),
    D = new Date(year,month,Dlast),
    DNlast = new Date(D.getFullYear(),D.getMonth(),Dlast).getDay(),
    DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
    calendar = '<tr>',
    month=["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];
if (DNfirst != 0) {
  for(var  i = 1; i < DNfirst; i++) calendar += '<td>';
}else{
  for(var  i = 0; i < 6; i++) calendar += '<td>';
}
  
var url_string = location.href; //window.location.href
var url = new URL(url_string);
var searchParam = url.searchParams.get("SearchEvent[date]");
var dateParts = searchParam;
for(var i = 1; i <= Dlast; i++) {
  if (i == new Date().getDate() && D.getFullYear() == new Date().getFullYear() && D.getMonth() == new Date().getMonth()) {
    calendar += '<td class="today">' + i;
  }else if(searchParam == D.getDate() + '.' + D.getMonth() + '.' + D.getFullYear()){
    calendar += '<td class="selected">' + i;
  }  else {
      calendar += '<td>' + i;
  }
  
  if (new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0) {
    calendar += '<tr>';
  }
}
for(var  i = DNlast; i < 7; i++) calendar += '<td>&nbsp;';
document.querySelector('#'+id+' tbody').innerHTML = calendar;
document.querySelector('#'+id+' thead td:nth-child(2)').innerHTML = month[D.getMonth()] +' '+ D.getFullYear();
document.querySelector('#'+id+' thead td:nth-child(2)').dataset.month = D.getMonth();
document.querySelector('#'+id+' thead td:nth-child(2)').dataset.year = D.getFullYear();
if (document.querySelectorAll('#'+id+' tbody tr').length < 6) {  // чтобы при перелистывании месяцев не "подпрыгивала" вся страница, добавляется ряд пустых клеток. Итог: всегда 6 строк для цифр
    document.querySelector('#'+id+' tbody').innerHTML += '<tr><td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;';
}
}
Calendar2("calendar2", new Date().getFullYear(), new Date().getMonth());
function Init() {
    var cells = document.querySelectorAll('#calendar2 tbody td');
    for (var i=0;i<cells.length;i++) {
        var day = cells[i].textContent;
        if(!day.trim()) continue;
        var tmp;
        cells[i].onmouseover = function() {
            tmp = this.style.backgroundColor;
            this.style.background = '#cccccc';
            this.style.cursor = 'pointer';
        }
        cells[i].onmouseout = function() {
            this.style.background = tmp;
            this.style.cursor = 'default';
        }
        cells[i].dataset.toggle = 'dropdown';
        cells[i].dataset.target = '#day-' + day;
        cells[i].setAttribute('id', 'day-' + day);
        
//        cells[i].appendChild(dropdownDiv);      
    }
}
// переключатель минус месяц
document.querySelector('#calendar2 thead tr:nth-child(1) td:nth-child(1)').onclick = function() {
  Calendar2("calendar2", document.querySelector('#calendar2 thead td:nth-child(2)').dataset.year, parseFloat(document.querySelector('#calendar2 thead td:nth-child(2)').dataset.month)-1);
  Init();
  LoadEvents( parseInt(document.querySelector('#calendar2 thead td:nth-child(2)').dataset.month)+1, 
              document.querySelector('#calendar2 thead td:nth-child(2)').dataset.year);
};
// переключатель плюс месяц
document.querySelector('#calendar2 thead tr:nth-child(1) td:nth-child(3)').onclick = function() {
  Calendar2("calendar2", document.querySelector('#calendar2 thead td:nth-child(2)').dataset.year, parseFloat(document.querySelector('#calendar2 thead td:nth-child(2)').dataset.month)+1);
  Init();
  LoadEvents( parseInt(document.querySelector('#calendar2 thead td:nth-child(2)').dataset.month)+1, 
    document.querySelector('#calendar2 thead td:nth-child(2)').dataset.year);
};

Date.prototype.toSovietDate = function() {
  var mm = this.getMonth() + 1; // getMonth() is zero-based
  var dd = this.getDate();

  return [
          (dd>9 ? '' : '0') + dd,
          (mm>9 ? '' : '0') + mm,
          this.getFullYear()
         ].join('.');
};



function LoadEvents(month, year) {
    $.ajax({
        url: '/events/ajax-calendar',
        type: 'get',
        async: false,
        data: {
            year: year,
            month: month
        },
        dataType: 'JSON',
        success: function (response){
            for(var i=0; i< response.length; i++) {
                var day = parseInt(response[i].date.substr(-2, 2));
                var dropdownDiv = $('<div></div>');
                dropdownDiv.addClass('dropdown-menu');
                dropdownDiv.addClass('dd-calendar');
                dropdownDiv.attr('aria-labelledby', 'day-' + day);
                $('#day-' + day).append(dropdownDiv);
                $('#day-' + day).css('background-color', '#97CBFF');
                $('#day-' + day).css('border-radius', '20px');
                $('#day-' + day + ' .dropdown-menu').append(
                        `<a onclick="location.href=this.href;return false;" class="dropdown-item" 
                                href="/event/${response[i].id}"><strong>${response[i].type.name}</strong> 
                                (${response[i].category.name})<p class="where">${response[i].city.name}</p></a>`
                        );
            }
        }
    });
}
    
    

var cd = new Date();
Init();
LoadEvents( parseInt(cd.getMonth())+1, cd.getFullYear());


        