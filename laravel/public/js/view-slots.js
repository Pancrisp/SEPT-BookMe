$('#whatever').click(function(){

    $date = '2017-03-26'; //eg only, please input the date selected by customer

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
            alert("Fail to get the bookings");
        });
});
