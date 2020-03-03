<script>
    $('body').ready(function () {
        @if(session ()->has ('error')|| session ()->has ('success'))
        $.notify({
            // options
            message: '{{session ()->has('error')?session ()->get('error'):session ()->get('success')}}',
            target: '_blank'
        },{
            // settings
            element: 'body',
            position: null,
            type: "{{session ()->has('error')?'warning':'success'}}",
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: true,
            placement: {
                from: "top",
                align: "right"
            },
            offset: 20,
            spacing: 10,
            z_index: 1031,
            delay: 5000,
            timer: 300,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated bounceInRight',
                exit: 'animated bounceOutRight'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 notify alert alert-{0}" role="alert">' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>'
        });
        @endif

        $('.header-action').click(function () {
            $(this).toggleClass('rot180');
            $(this).parents('div.card').find('div.card-block').slideToggle();
        });
    });
</script>