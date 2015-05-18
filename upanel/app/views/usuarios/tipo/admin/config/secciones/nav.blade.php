
<div class="col-lg-3" id="menu-crear-app">
    <ul class="nav nav-pills nav-stacked">
        <li @if(Request::is('config/general')) class="active" @endif>
             <a href="{{URL::to("config/general")}}"><span class="glyphicon glyphicon-cog"></span> 
                {{trans("interfaz.menu.principal.config.general")}}</a>
        </li>
    </ul>
</div>