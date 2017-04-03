$('#search-button').click(function() {

    $date = document.querySelector('#date').value;

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
