<?php
$form_data = array('action' => 'UPanelControladorUsuario@cambiarContrasenaPost', 'method' => 'POST', 'rol' => 'form');
?>
@extends('interfaz/plantilla')

@section("titulo") Perfil {{Auth::user()->nombres}}@stop

@section("contenido") 

<h1>Cambia tu contraseña</h1>
@include("interfaz/mensaje/index",array("id_mensaje"=>2))

{{Form::model(null, $form_data, array('role' => 'form')) }}
<table class="table table-striped">
    <tr><th>Contraseña actual</th><td>    {{ Form::password('contra-actual',array('placeholder' => 'Escribe tu contraseña actual', 'class' => 'form-control')) }}</td></tr>
    <tr><th>Contraseña nueva</th><td>{{ Form::password('contra-nueva',array('placeholder' => 'Escribe una nueva contraseña', 'class' => 'form-control')) }}</td></tr>
    <tr><th>Repetir nueva contraseña</th><td>{{ Form::password('contra-nueva-rep',array('placeholder' => 'Vuelve a escribir la nueva contraseña', 'class' => 'form-control')) }}</td></tr>
</table>

<div class="well-lg text-center">
    {{ Form::button("Cambiar contraseña", array('type' => 'submit', 'class' => 'btn btn-primary')) }}    
    {{ Form::close() }}
</div>

<hr>
<hr>

<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Consejos para escoger una contraseña de calidad</h3>
    </div>
    <div class="container" style="padding: 15px;">
        El objetivo a la hora de seleccionar una contraseña es ofrecer el mayor grado de dificultad posible a un posible intruso que intente descubrirlo. 
        <ul>
            <li><b>NO</b> utilice su nombre del usuario ni posibles combinaciones. 
            </li><li><b>NO</b> utilice sus apellidos ni posibles combinaciones. 
            </li><li><b>NO</b> utilice los nombres de los hijos o cónyuges. 
            </li><li><b>NO</b> utilice información fácil de obtener relacionada con Ud.: DNI, números de teléfono, matrícula del coche .... 
            </li><li><b>NO</b> utilice una palabra contenida en un diccionario. 
            </li><li><b>SÍ</b> conviene alternar mayúsculas con minúsculas. 
            </li><li><b>SÍ</b> conviene utilizar caracteres no alfabéticos. 
            </li><li><b>SÍ</b> es necesario que sea fácil de recordar sin tener que escribirlo en papel. 
            </li><li><b>SÍ</b> conviene utilizar una contraseña que se pueda teclear rápidamente. 
            </li></ul>
    </div>

    <div class="panel-heading">
        <h2 class="panel-title">Mantenga la contraseña segura</h2>
    </div>
    <div class="container" style="padding: 15px;">
        <ul>
            <li>Memorice la contraseña 
            </li><li>No debe escribirla en ningún papel, tarjeta... 
            </li><li>Nunca dé la contraseña a otros usuarios. 
            </li><li>Cambie la contraseña de vez en cuando (1 vez cada 2 ó 3 meses) 
            </li></ul>
    </div>
</div>

@stop