<?php

namespace Appsthergo\API;

require 'Config.php';
require 'Lang.php';
require 'Util.php';
require 'Css.php';

use Appsthergo\API\Config as Config;
use Appsthergo\API\Lang as Lang;
use Appsthergo\API\Util as Util;
use Appsthergo\API\Css as Css;

class Html extends Lang {

    public function formCreateUserHeader($title) {
        $output = " <header id='aptg-header-create-user' style='text-align:center;'>";
        $output.="<h1>" . $title . "</h1>";
        $output.=" </header>";
        return $output;
    }

    //********************************************************************
    //FORM CREATE USER****************************************************
    //********************************************************************


    public function formCreateUser() {

        $output = "<div id='aptg-form-create-user' style='width: 100%;text-align:center;'>";
        $output.="<h2>" . parent::printText(parent::CREATE_USER_FORM_DESCRIPTION) . "</h2>";
        $output.="<form id='form_cuenta' method='post' action='" . Config::URL_POST_CREATE_USER . "'>";
        $output.=" <div class='grid'>";
        $output.="    <div style='" . Css::trap_msj_error . "display:none;' id='msj-error'>" . parent::printText(parent::MSJ_ERROR_CREATE_FORM) . "</br><ul style='margin: 5px;' id='error-description'></ul></div>";
        $output.="    <p class='cell-2' " . Css::trap_cell2 . ">";
        $output.="        <label " . Css::trap_cell_label . " for='nombre1'>" . parent::printText(parent::CREATE_USER_FORM_TEXT_INPUT_NAME1) . "</label>";
        $output.="        <input " . Css::trap_cell_input_text . " type='text' name='nombre1' id='nombre1' value=''>";
        $output.="    </p>";
        $output.="     <p class='cell-2' " . Css::trap_cell2 . ">";
        $output.="         <label " . Css::trap_cell_label . " for='nombre2'>" . parent::printText(parent::CREATE_USER_FORM_TEXT_INPUT_NAME2) . "</label>";
        $output.="        <input " . Css::trap_cell_input_text . " type='text' name='nombre2' id='nombre2' value=''>";
        $output.="     </p>";
        $output.="     <p class='cell-2' " . Css::trap_cell2 . ">";
        $output.="         <label " . Css::trap_cell_label . " for='apellidos'>" . parent::printText(parent::CREATE_USER_FORM_TEXT_INPUT_LASTNAME) . "</label>";
        $output.="         <input " . Css::trap_cell_input_text . " type='text' name='apellidos' id='apellidos' value=''>";
        $output.="     </p>";
        $output.="       <p class='cell-2' " . Css::trap_cell2 . ">";
        $output.="          <label " . Css::trap_cell_label . " for='email'>" . parent::printText(parent::CREATE_USER_FORM_TEXT_INPUT_EMAIL) . "</label>";
        $output.="          <input " . Css::trap_cell_input_text . " type='text' name='email' id='email' value=''>";
        $output.="      </p>";
        $output.="       <p class='cell-2' " . Css::trap_cell2 . ">";
        $output.="          <label " . Css::trap_cell_label . " for='password'>" . parent::printText(parent::CREATE_USER_FORM_TEXT_INPUT_PASSWORD) . "</label>";
        $output.="           <input " . Css::trap_cell_input_text . " type='password' name='password' id='password' value=''>";
        $output.="       </p>";
        $output.="       <p class='cell-2' " . Css::trap_cell2 . ">";
        $output.="           <label " . Css::trap_cell_label . " for='password_rep'>" . parent::printText(parent::CREATE_USER_FORM_TEXT_INPUT_REPEAT_PASSWORD) . "</label>";
        $output.="           <input " . Css::trap_cell_input_text . " type='password' name='password_rep' id='password_rep' value=''>";
        $output.="       </p>";
        $output.="       <p class='cell-button' " . Css::trap_cell2 . ">";
        $output.="           <input " . Css::trap_button . " type='button' id='submitted' name='submitted' value='" . parent::printText(parent::CREATE_USER_FORM_SUBMIT) . "'>";
        $output.="        </p>";
        $output.="    </div>";
        $output.="      <input type='hidden' name='url' value='" . Util::getUrlCurrent() . "'/>";
        $output.="  </form>";
        $output.=" </div>";


        return $output;
    }

    public function formCreateUser_script() {

        $output = "<script>";
        $output.="    jQuery('#submitted').click(function () {";
        $output.="       var val = true;";
        $output.="      var nombre1 = jQuery('#nombre1').val();";
        $output.="      var nombre2 = jQuery('#nombre2').val();";
        $output.="      var apellidos = jQuery('#apellidos').val();";
        $output.="     var email = jQuery('#email').val();";
        $output.="      var contra = jQuery('#password').val();";
        $output.="      var contra_rep = jQuery('#password_rep').val();";
        $output.="     jQuery('#msj-error').fadeOut();";
        $output.="     jQuery('#error-description').html('');";
        $output.="     if (!validarNombre(nombre1)) {";
        $output.="          val = false;";
        $output.="          jQuery('#error-description').append('<li>" . parent::printText(parent::CREATE_USER_FORM_ERROR_INPUT_NAME) . "</li>');";
        $output.="      }";
        $output.="     if (!validarNombre(apellidos)) {";
        $output.="         val = false;";
        $output.="         jQuery('#error-description').append('<li>" . parent::printText(parent::CREATE_USER_FORM_ERROR_INPUT_LASTNAME) . "</li>');";
        $output.="      }";
        $output.="       if (!validarEmail(email)) {";
        $output.="           val = false;";
        $output.="           jQuery('#error-description').append('<li>" . parent::printText(parent::CREATE_USER_FORM_ERROR_INPUT_EMAIL) . "</li>');";
        $output.="      }";
        $output.="     if (contra.length < 6) {";
        $output.="          val = false;";
        $output.="          jQuery('#error-description').append('<li>" . parent::printText(parent::CREATE_USER_FORM_ERROR_INPUT_PASSWORD) . "</li>');";
        $output.="      }";
        $output.="        if (contra != contra_rep) {";
        $output.="          val = false;";
        $output.="          jQuery('#error-description').append('<li>" . parent::printText(parent::CREATE_USER_FORM_ERROR_INPUT_REPEAT_PASSWORD) . "</li>');";
        $output.="       }";
        $output.="       if (val)";
        $output.="           jQuery('#form_cuenta').submit();";
        $output.="        else";
        $output.="            jQuery('#msj-error').slideToggle();";
        $output.="      });";
        $output.="  ";
        $output.="   function validarNombre(name)";
        $output.="     {";
        $output.="        if (name.length < 3) {";
        $output.="           return (false);";
        $output.="       }";
        $output.="       var checkOK = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890.ÁÉÍÓÚ' + 'abcdefghijklmnñopqrstuvwxyzáéíóú ';";
        $output.="       var checkStr = name;";
        $output.="       var allValid = true;";
        $output.="       for (i = 0; i < checkStr.length; i++) {";
        $output.="           ch = checkStr.charAt(i);";
        $output.="          for (j = 0; j < checkOK.length; j++)";
        $output.="            if (ch == checkOK.charAt(j))";
        $output.="                break;";
        $output.="       if (j == checkOK.length) {";
        $output.="           allValid = false; break;";
        $output.="       }";
        $output.="   }";
        $output.="   if (!allValid) {";
        $output.="        return (false);";
        $output.="    }";
        $output.="    return (true);";
        $output.="    }";
        $output.="   function validarEmail(email) {";
        $output.="       expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;";
        $output.="        if (expr.test(email))";
        $output.="          return true;";
        $output.="      else";
        $output.="          return false;";
        $output.="    } ";
        $output.=" </script>";

        return $output;
    }

    public function msj_error_email_exists() {
        $output = " <div  id='aptg-msj-error' style='" . Css::trap_msj_error . "'><p>" . parent::printText(parent::CREATE_USER_FORM_ERROR_EMAIL_EXISTS) . "</p></div>";
        return $output;
    }

    public function msj_success_create_user($email, $link_login) {
        $output = "<div id='aptg-msj' " . Css::trap_content_msj . "><p>" . parent::printText(parent::CREATE_USER_FORM_MSJ_SUCCESS, array("email" => $email, "link_login" => $link_login."?response=refused&email=".$email)) . "</p></div>";
        return $output;
    }

    //********************************************************************
    //FORM LOGIN**********************************************************
    //********************************************************************

    public function formLogin() {
        $output = " <div id='aptg-login' style='width: 100%;text-align:center;'>";
        $output.="  <form id='form_cuenta' style='width: 50%;display: inline-block;min-width:340px;' method='post' action='" . Config::URL_POST_LOGIN . "'>";
        $output.="    <h2>" . parent::printText(parent::LOGIN_FORM_DESCRIPTION_LABEL) . "</h2>";
        $output.="  <div class='grid'>";
        $output.="     <p class='cell' " . Css::trap_cell . ">";
        $output.="        <label " . Css::trap_cell_label . " for='email'>" . parent::printText(parent::LOGIN_FORM_INPUT_EMAIL) . "</label>";
        $output.="       <input " . Css::trap_cell_input_text . " type='text' name='email' id='email' value='";
        $output.=(isset($_GET['email'])) ? $_GET['email'] : '';
        $output.="'></p>";
        $output.="    <p class='cell' " . Css::trap_cell . ">";
        $output.="        <label " . Css::trap_cell_label . " for='contrasena'>" . parent::printText(parent::LOGIN_FORM_INPUT_PASSWORD) . "</label>";
        $output.="        <input " . Css::trap_cell_input_text . " type='password' name='contrasena' id='contrasena' value=''>";
        $output.="    </p>";
        $output.="    <p class='cell-2' " . Css::trap_cell2 . ">";
        $output.="      <span style='text-align:left;display: block;'><input type='checkbox' name='recordarme'/> " . parent::printText(parent::LOGIN_FORM_CHECK_REMEMBER) . "</span>";
        $output.="     </p>";
        $output.="     <p class='cell-2' " . Css::trap_cell2 . ">";
        $output.="          <span style='text-align:right;display: block;'><a href='" . Util::UrlFiltrate(Util::getUrlCurrent()) . "?stage=recovery'>" . parent::printText(parent::LOGIN_FORM_LINK_RECOVERY) . "</a></span>";
        $output.="      </p>";
        $output.="       <input type='hidden' name='url' value='" . Util::UrlFiltrate(Util::getUrlCurrent()) . "'/>";
        $output.=" <p class='buttons' class='cell' " . Css::trap_cell . ">";
        $output.="<span style='wdith:100%;display:block;margin-top:20px;'></span>";
        $output.="    <input  " . Css::trap_button . " type='submit' id='submitted' name='submitted' value='" . parent::printText(parent::LOGIN_FORM_SUBMIT) . "'>";
        $output.="  </p>";
        $output.="    </div>";
        $output.="   </form>";
        $output.="     </div>";

        return $output;
    }

    public function msj_logout() {
        $output = " <div id='aptg-msj-logout' " . Css::trap_msj_logout . ">" . parent::printText(parent::LOGIN_FORM_MSJ_LOGOUT) . "</div>";
        return $output;
    }

    public function msj_send_email_confirmation() {
        $output = "<div id='aptg-msj-success' " . Css::trap_msj_success . ">" . parent::printText(parent::LOGIN_FORM_SEND_EMAIL_CONFIRMATION, array("email" => (isset($_GET["email"])) ? $_GET["email"] : "")) . "</div>";
        return $output;
    }

    public function msj_fail_confirmation() {

        $text = parent::printText(parent::LOGIN_FORM_MSJ_FAIL_CONFIRMATION, array(
                    "email" => (isset($_GET["email"])) ? $_GET["email"] : "",
                    "link" => str_replace(":id_user", (isset($_GET["user"])) ? $_GET["user"] : 0, Config::URL_GET_FORWARD_ACTIVATION_ACCOUNT)));

        $output = "<div id='aptg-msj-error' style='" . Css::trap_msj_error . "'><p>" . $text . "</p></div>";

        return $output;
    }

    public function msj_login_fail() {
        $output = "<div id='aptg-msj-error' style='" . Css::trap_msj_error . "'><p>" . parent::printText(parent::LOGIN_FORM_MSJ_FAIL_LOGIN) . "</p></div>";
        return $output;
    }

    public function msj_fail_password_recovery() {
        $output = "<div id='aptg-msj-error' style='" . Css::trap_msj_error . "'><p>" . parent::printText(parent::LOGIN_FORM_FAIL_RECOVERY_PASSWORD) . "</p></div>";
        return $output;
    }

    public function msj_recovery_password() {
        $output = "<header style='text-align:center;'>" . parent::printText(parent::LOGIN_FORM_MSJ_RECOVERY_PASSWORD) . "</header>";
        return $output;
    }

    public function msj_confirmation_email() {
        $output = "<header style='text-align:center;'><h1>" . parent::printText(parent::LOGIN_FORM_MSJ_CONFIRMATION_MAIL) . "</h1></header>";
        return $output;
    }

    //********************************************************************
    //FORM RECOVERY PASSWORD**************************************************
    //********************************************************************

    public function formRecoveryPassword($lang) {
        $output = "<div id='aptg-form-recovery' style='width: 100%;text-align:center;'>";
        $output.="<form id='form' method='post' style='width: 50%;display: inline-block;min-width:340px;' action='" . Config::URL_POST_RECOVERY_PASSWORD . "?lang=" . $lang . "'>";
        $output.="    <h2>" . parent::printText(parent::RECOVERY_FORM_TITLE) . "</h2>";
        $output.="    <div  style='" . Css::trap_msj_error . "display:none;' id='msj-error' ></div>";
        $output.="    <div class='grid' style='max-width: 500px;margin: auto;'>";
        $output.="<p class='cell' " . Css::trap_cell . ">";
        $output.="    <label " . Css::trap_cell_label . " for='email'>" . parent::printText(parent::RECOVERY_FORM_INPUT_EMAIL) . "</label>";
        $output.="   <input " . Css::trap_cell_input_text . " type='text' name='email' id='email' value=''>";
        $output.="<input type='hidden' name='url' value='" . Util::getUrlCurrent() . "'/>";
        $output.=" </p>";
        $output.=" <p class='cell' " . Css::trap_cell . ">";
        $output.="     <input " . Css::trap_button . " type='button' id='submitted' class='contact-form-button' name='submitted' value='" . parent::printText(parent::RECOVERY_FORM_SUBMIT) . "'>";
        $output.=" </p>";
        $output.=" </div>";
        $output.="     </form>";
        $output.="  </div>";

        return $output;
    }

    public function formRecoveryPassword_script() {
        $output = "<script>";
        $output.=" jQuery('#submitted').click(function () {";
        $output.= "  var email = jQuery('#email').val();";
        $output.= "   var val = true;";
        $output.= " if (!validarEmail(email)) {";
        $output.= "      jQuery('#msj-error').html('".parent::printText(parent::RECOVERY_FORM_ERROR_INPUT_EMAIL)."');";
        $output.= "      jQuery('#msj-error').slideToggle();";
        $output.= "      val = false;";
        $output.= "  }";
      
        $output.= "  if (val)";
        $output.= "       jQuery('#form').submit();";
        $output.= "  });";
        $output.= "  function validarEmail(email) {";
        $output.= "      expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;";
        $output.= "      if (expr.test(email))";
        $output.= "          return true;";
        $output.= "      else";
        $output.= "          return false;";
        $output.= "   }";
        $output.= " </script>";

        return $output;
    }

    public function msj_rp_response_account_inactive() {

        $text_description = parent::printText(parent::RECOVERY_FORM_MSJ_ACCOUNT_INACTIVE, array(
                    "email" => (isset($_GET["email"])) ? $_GET["email"] : "",
                    "link" => str_replace(":id_user", (isset($_GET["user"])) ? $_GET["user"] : 0, Config::URL_GET_FORWARD_ACTIVATION_ACCOUNT)));

        $output = "<div id='aptg-msj-error' style='" . Css::trap_msj_error . "'>";
        $output.="<div style='text-align: center;font-size: 17pt;'><b>" . parent::printText(parent::RECOVERY_FORM_MSJ_ACCOUNT_INACTIVE_TITLE) . "</b></div>";
        $output.="<p>" . $text_description . "</p></div>";
        return $output;
    }

    public function msj_rp_response_fail_email() {
        $output = "<div id='aptg-msj-error' style='" . Css::trap_msj_error . "'><p>" . parent::printText(parent::RECOVERY_FORM_MSJ_FAIL_EMAIL, array(
                    "email" => (isset($_GET["email"])) ? $_GET["email"] : "")) . "</p></div>";
        return $output;
    }

    public function msj_rp_response_send_email() {
        $output = " <header>";
        $output.="<h1>" . parent::printText(parent::RECOVERY_FORM_MSJ_SEND_EMAIL_TITLE) . "</h1>";
        $output.=" </header>";
        $output.=" <div id='aptg-msj' " . Css::trap_content_msj . ">";
        $output.="  <p>" . parent::printText(parent::RECOVERY_FORM_MSJ_SEND_EMAIL, array(
                    "email" => (isset($_GET["email"])) ? $_GET["email"] : "")) . "</p>";
        $output.="  </div>";
        return $output;
    }

}
