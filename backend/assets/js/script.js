yii.script = (function ($) {
    var pub = {
        initRegisterUserForm: function () {
            //console.log($('#user-username').length);
            // autofill email
            $('#user-username').on('keyup', function (e) {
                var username = $('#user-username').val();
                $('#user-email').val(username + '@sibghk.ru');
            });

            // autofill fio
            $('body').on('change', '#userprofile-card_id', function (e) {
                var card_id = $('#userprofile-card_id').val();
                $.ajax({
                    url: '/user-manager/get-card?id=' + card_id,
                    type: 'get',
                    dataType: 'json',
                    success: function (data) {
                        //console.log('/user-manager/get-card?id=' + card_id);

                        var stabnum = data.stabnum;
                        var secondname = data.secondname;
                        var firstname = data.firstname;
                        var thirdname = data.thirdname;
                        $('#userprofile-stabnum').val(stabnum);
                        $('#userprofile-secondname').val(secondname);
                        $('#userprofile-firstname').val(firstname);
                        $('#userprofile-thirdname').val(thirdname);

                    }
                });

            });
        }
    }
    return pub;
})(jQuery)