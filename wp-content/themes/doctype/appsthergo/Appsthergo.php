<?php

namespace Appsthergo;

/**
 * Company: Appsthergo Technologies S.A.S 
 * Developer: John Ospina
 * Year: 2015
 * Version: 1.0
 */
/** Requeriments
 *  - From Version PHP 5.3
 *  
 */
include 'API/Html.php';

use Appsthergo\API\Html as Html;

class Appsthergo extends Html {

    var $privateKey;

    function __construct($privateKey, $lang = "ES") {
        $this->privateKey = $privateKey;
        $this->lang = strtoupper($lang);
    }

    public function printFormCreateUser($title, $link_login) {
        $response = (!isset($_GET["response"])) ? null : $_GET["response"];
        $email = (!isset($_GET["email"])) ? null : $_GET["email"];


        $title = ($response == "success") ? parent::printText(parent::CREATE_USER_FORM_TITLE_SUCCESS) : $title;

        print $this->formCreateUserHeader($title);

        if ($response == "success"):
            print $this->msj_success_create_user($email, $link_login);
        endif;

        //Print error message when email exist
        if ($response == "error"):
            print $this->msj_error_email_exists();
        endif;

        //Print the form for create user
        if ($response == null || $response == "error"):
            print $this->formCreateUser();
            print $this->formCreateUser_script();
        endif;
    }

    public function printFormLogin() {
        $response = (!isset($_GET["response"])) ? null : $_GET["response"];
        $stage = (!isset($_GET["stage"])) ? null : $_GET["stage"];

//Inicio de sesion automatico si el usuario ha recordado sus credenciales de acceso
        if ($response == null && $stage == null)
            die("<script>location.href = '" . API\Config::URL_INTO_UPANEL . "'</script>");

        //Form Recovery
        if (($recovery = $this->printFormRecovery()) == true)
            return $recovery;


        if ($response == "confirmation"):
            print $this->msj_confirmation_email();
        endif;

        if ($response == "recovery"):
            print $this->msj_recovery_password();
        endif;

        if ($response == "fail-password"):
            print $this->msj_fail_password_recovery();
        endif;

        if ($response == "fail"):
            print $this->msj_login_fail();
        endif;

        if ($response == "fail-confirmation"):
            print $this->msj_fail_confirmation();
        endif;

        if ($response == "send-confirmation"):
            print $this->msj_send_email_confirmation();
        endif;

        if ($response == "logout"):
            print $this->msj_logout();
        endif;

        print $this->formLogin();
    }

    private function printFormRecovery() {
        $response = (!isset($_GET["response"])) ? null : $_GET["response"];
        $stage = (!isset($_GET["stage"])) ? null : $_GET["stage"];

        if ($stage != "recovery")
            return false;

        if ($response == "inactive"):
            print $this->msj_rp_response_account_inactive();
        endif;

        if ($response == "fail"):
            print $this->msj_rp_response_fail_email();
        endif;

        if ($response == "send"):
            print $this->msj_rp_response_send_email();
            return true;
        endif;

        print $this->formRecoveryPassword($this->lang);

        print $this->formRecoveryPassword_script();

        return true;
    }

}
