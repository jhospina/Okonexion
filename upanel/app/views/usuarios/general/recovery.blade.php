<html>
    <head>
        <title>{{trans("usuario.rc.titulo")}} @include("interfaz/titulo-pagina")</title>
        <link rel="shortcut icon" href="{{URL::to("/assets/img/favicon.png")}}">

        {{ HTML::style('assets/plugins/bootstrap/css/bootstrap.css', array('media' => 'screen')) }}
        {{ HTML::style('assets/plugins/bootstrap/css/bootstrap-theme.css', array('media' => 'screen')) }}

        {{-- jQuery (necessary for Bootstraps JavaScript plugins) --}}
        {{ HTML::script('assets/js/jquery.js') }}
        {{ HTML::script('assets/js/jquery-1.10.2.js') }}

    </head>
    <body style="background: gainsboro;">

        <div class="container text-center" style="background: white;  max-width: 50%;margin-top:150px;-webkit-border-radius: 10px;
             -moz-border-radius: 10px;
             border-radius: 10px;border: 1px rgb(181, 181, 181) solid;" id="container">

            <div class="contact-form-wrapper" style="margin-bottom:50px;">
                <form id="form" method="post" action="{{URL::to("recovery")}}">
                    <h2 style="text-transform: uppercase;">{{trans("usuario.rc.head")}}</h2>
                    <div class="alert-danger alert"  id="msj-error" style="display:none;"></div>

                    <div style="margin:auto;width:70%">
                        <div class="col-lg-6 input-lg text-left">
                            {{trans("usuario.rc.input.contra")}}:
                        </div>
                        <div class="col-lg-6 input-lg">
                            <input class="form-control" type="password" name="contra" id="contra" value="">
                        </div>
                        <div class="col-lg-6 input-lg text-left">
                            {{trans("usuario.rc.input.rep.contra")}}:
                        </div>
                        <div class="col-lg-6 input-lg">
                            <input class="form-control" type="password" name="contra2" id="contra2" value="">
                        </div>
                        <input type="hidden" name="user" value="{{$usuario}}">
                        <input type="hidden" name="code" value="{{$codigo}}">
                        <div class="col-lg-12" style="margin-top: 20px;margin-bottom: 20px;">
                            <input class="btn btn-primary" type="button" id="submitted" class="contact-form-button" name="submitted" value="{{trans("usuario.rc.submit")}}">
                        </div>
                    </div>
            </div>
        </form>
    </div>
</div>

<script>

    jQuery("#submitted").click(function () {
        var contra = jQuery("#contra").val();
        var contra2 = jQuery("#contra2").val();
        var val = true;

        jQuery("#msj-error").hide();
        jQuery("#msj-error").html("");

        if (contra.length >= 6) {
            if (contra != contra2) {
                val = false;
                jQuery("#msj-error").html("{{trans('menu_usuario.cambiar_contrasena.post.error02')}}");
            }
        } else {
            val = false;
            jQuery("#msj-error").html("{{trans('menu_usuario.cambiar_contrasena.post.error01')}}");
        }

        if (val)
            jQuery("#form").submit();
        else {
            jQuery("#contra").val("");
            jQuery("#contra2").val("");
            jQuery("#msj-error").slideToggle();
        }
    });
</script>
</body>
</html>