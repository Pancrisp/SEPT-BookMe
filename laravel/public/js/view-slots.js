var currentDate = new Date();
var months = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

var day = currentDate.getDate();
var month = months[currentDate.getMonth()];
var year = currentDate.getFullYear();
var currentDate = day + " " + month + ", " + year;

$(document).ready(function() {
    $('#date').attr("placeholder", currentDate);

    $(function() {
        $('#date').datepicker({ minDate: 0, maxDate: '+1M', dateFormat: 'd M, yy' });
    });
});

// AJAX for viewing available booking slots
$('#search-button').click(function() {

    $date = document.querySelector('#date').value;

    $.ajax({
        url: '/bookings/getByDate',
        type: 'get',
        data: {
            'date': $date
        },
        success: function(response) {
            // var res = JSON.parse(response);
            console.log(response);
            $('.timeslot').html(response);
        }
    })
        .error(function(response) {
            alert("Unable to retrieve bookings");
        });
});
