var currentDate = new Date().toISOString().slice(0,10);

$(document).ready(function() {
    $('#date').attr("placeholder", currentDate);

    $(function() {
        $('#date').datepicker({ minDate: 0, maxDate: '+1M', dateFormat: 'yy-mm-dd' });
    });
});

// sets date value for roster date input field
$("#roster-date").datepicker({
    minDate: 0,
    maxDate: '+1M',
    dateFormat: 'yy-mm-dd',
    onSelect: function() {
        var date = document.querySelector('#roster-date').value;
        console.log(date);
    }
});

// AJAX for viewing available booking slots
$('#search-button').click(function() {

    var date = document.querySelector('#date').value;
    console.log(date);

    $.ajax({
        url: '/bookings/getByDate',
        type: 'get',
        data: {
            'date': date
        },
        success: function(response) {
            console.log(response)
            // for (var i = 0; i < response.length; i++) {
            //     console.log(response[i].start_time);
            //     document.querySelector('.flex-container').innerHTML += ('<li class="flex"><a class="timeslot block-btn" href="#"></a></li>');
            //     $('.timeslot').html(response[i].start_time);
            // }
        }
    })
        .error(function(response) {
            alert("Unable to retrieve bookings");
        });
});
