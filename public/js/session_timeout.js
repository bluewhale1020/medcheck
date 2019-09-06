    /*
     *   this script is for manage the logout of timeout
     *   if user is inactive for 15 min
     *   he will be logout : 
     *
     * */
    // var logout = 'Are you sure to logout?';
    var logout = '一定時間操作されなかったため、ログアウトします。';
    var lifetime = 60000 * 15; // 60000 * 15
    var timeout;
    var url =  '/medcheck/public/logout'; // route path;
    document.onmousemove = function(){
        clearTimeout(timeout);
            timeout = setTimeout(function () {
                document.onmousemove = null;
                Swal.fire({
                    // title: '本当に削除しますか?',
                    text: logout,
                    type: 'warning',
                    // showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    // cancelButtonColor: '#d33',
                    confirmButtonText: 'OK'
                    // showConfirmButton: false,
                    // timer: 1500                    
                  }).then((result) => {
                    //logout
                    var redirect = $.ajax({
                        cache: false,
                        url: url,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': window.Laravel.csrfToken
                        },
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            window.location.href = url;
                        }
                    });
    

                  });


            }, lifetime);
        };