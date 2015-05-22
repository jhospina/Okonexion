@section("script")
{{ HTML::script('assets/plugins/switchery/switchery.js') }}
{{ HTML::script('assets/plugins/fileinput/js/fileinput.js') }}
{{ HTML::script('assets/jscode/util.js') }}

<script>
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function (html) {
        var switchery = new Switchery(html, {className: "switchery switchery-small"});

    });
</script>

<script>

    jQuery(".js-switch").change(function () {

        var config = "#" + $(this).attr("data-for");

        if ($(this).is(':checked'))
            $(config).val("{{Util::convertirBooleanToInt(true)}}");
        else
            $(config).val("{{Util::convertirBooleanToInt(false)}}");

    });

    jQuery("#btn-guardar").click(function () {
        jQuery(this).html("<span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> {{trans('otros.info.guardando')}}...");
        jQuery(this).attr("disabled", "disabled");
        setTimeout(function () {
            $("#form-config").submit();
        }, 1500);
    });
</script>

@stop