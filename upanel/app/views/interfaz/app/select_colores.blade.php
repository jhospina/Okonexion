<?php
$colores = AppDesing::coloresFondo();
?>

<select name="{{$name}}" class="{{$class}}">
    @foreach($colores as $index => $color)
    <option value="{{$color}}" @if($colorDefecto==$color) selected @endif>{{$color}}</option>
    @endforeach
</select>