

//Poll Voting Code
    $(document).on('change', '.poll_radio', function () {
        var vote_val = $(this).val();
        $('#submit_vote_btn').show();
        $('.vote-login-details').show();
        $('#submit_vote_btn').show();

$(document).on('click','.view_results_btn',function(e){
    e.preventDefault();
    $('#poll_voting_form').hide();
    $('#submit_vote_btn').hide();
});

$(document).on('click','.view_options_btn',function(e){
    e.preventDefault();
    $('#poll_voting_form').show();
    $('#submit_vote_btn').show();
});

//Poll Voting Store
        $(document).on('click', '#submit_vote_btn', function (e) {
            e.preventDefault();

            let el = $(this);
            const form = $('#poll_voting_form');
            let route = "{{route('frontend.poll.vote.store')}}";
            let name = form.find('#voter_name').val();
            let email = form.find('#voter_email').val();
            let id = form.find('#id').val();

            $.ajax({
                url: route,
                method: 'POST',
                data: {
                    _token: "{{csrf_token()}}",
                    id: id,
                    name: name,
                    email: email,
                    vote_name: vote_val
                },

                success: function (data) {
                    form.find('.error-wrap').html('<div class="alert alert-' + data.type + '">' + data.msg + '</div>');

                },
                error: function (data) {
                    console.log(data);
                    var response = data.responseJSON.errors;
                    form.find('.error-wrap').html('<ul class="alert alert-danger"></ul>');
                    $.each(response, function (value, index) {
                        form.find('.error-wrap ul').append('<li>' + index + '</li>');
                    });
                    el.text('{{__("Submit Vote")}}');
                }
            });
        })
    });


