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
    $('#date').datepicker({ minDate: 0, maxDate: '+1M', dateFormat: 'd M, yy' });
    $('#date').attr("data-date", 'yy-mm-dd');
});

// obtains the date value when user selects a date from the calendar
$('#date').datepicker({
    onSelect: function() {
        var dateObject = $(this).datepicker('getDate');
        console.log(dateObject);
    }
});


$('#date').datepicker('getDate');

$('#search-button').click(function() {

    $date = document.querySelector('#date').attr("data-date");

    $.ajax({
        url: '/bookings/getByDate',
        type: 'get',
        data: {
            'date': $date
        },
        success: function(response) {
            // var res = JSON.parse(response);
            console.log(response); // this will help u to see what is in the res
        }
    })
        .error(function(response) {
            alert("Unable to retrieve bookings");
        });
});
