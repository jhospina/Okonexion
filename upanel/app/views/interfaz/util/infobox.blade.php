<?php
if (!isset($infobox_div))
    $infobox_div = 3;
?>

<div class="col-lg-{{$infobox_div}} stats-info-box" style="border:1px {{$infobox_color}} solid;">

    <div @if(isset($infobox_link_info)) onclick="location.href ='{{$infobox_link_info}}';" @endif class="col-lg-12 info" style="background-color: {{$infobox_color}}; ">
          <div class="col-lg-4"><span class="gly glyphicon {{$infobox_icon}}"></span></div>
        <div class="col-lg-8 text-right">
            <span class="num-cant">{{$infobox_cant}}</span>
            <label>{{$infobox_label}}</label>
        </div>
    </div>
    <div @if(isset($infobox_link_foot)) onclick="location.href ='{{$infobox_link_foot}}';" @endif class="col-lg-12 foot" style="border:1px {{$infobox_color}} solid;background-color: {{$infobox_color}};">
        <div class="col-xs-10">
            {{$infobox_descripcion}}</div>
        <div class="col-xs-2 text-right">
            <span class="glyphicon glyphicon-circle-arrow-right"></span>
        </div>
    </div>
</div>