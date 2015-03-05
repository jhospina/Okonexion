<?php
if (!isset($seleccionados))
    $seleccionados = null;
?>
@foreach($terms as $term)
<div class="checkbox" style="margin: 0px;">
    <label><input type="checkbox" name="term-{{$term->id}}" value="{{$term->id}}" 
                  @if(!is_null($seleccionados))
                  @foreach($seleccionados as $term_cont)
                  @if($term_cont->id==$term->id)
                  {{"checked"}}
                   @endif
                  @endforeach
                  @endif

                  /> {{$term->nombre}}</label>
</div>
@endforeach

