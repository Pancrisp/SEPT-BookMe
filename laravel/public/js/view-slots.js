var currentDate = new Date();
var months = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

var day = currentDate.getDate();
var month = months[currentDate.getMonth()];
var year = currentDate.getFullYear();
var currentDate = day + " " + month + ", " + year;

$(document).ready(function() {
    $('#date').attr("placeholder", currentDate);
});

$(function () {
    $('#date').datepicker({ minDate: 0, maxDate: '+3M', dateFormat: 'd M, yy' });
});

$('#search-button').click(function() {

    $date = document.querySelector('#date').attr("data-date");

    $.ajax({
        url: '/bookings/getByDate',
        type: 'get',
        data: {
            'date': $date
        },
        success: function(response) {
            var res = JSON.parse(response);
            console.log(res); // this will help u to see what is in the res
        }
    })
        .error(function(response) {
            alert("Unable to retrieve bookings");
        });
});
