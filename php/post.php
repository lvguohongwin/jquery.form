<?php

/*
 * Maciej Małecki
 * smt116(at)gmail.com
 * http://github.com/smt116/jquery.form/
 * 
 * MIT License http://www.opensource.org/licenses/mit-license.php
 */

error_reporting(NULL);

/**
 * Klasa odpowiedzialna za przekazanie informacji z php do js 
 */
final class json {

    /**
     * @param array $array tablica do przekazania do js
     */
    function __construct($array) {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/json");
        echo json_encode($array);
    }

}

/**
 * Klasa odpowiedzialna za wysłanie formularza i zwrócenie komunikatu
 */
final class parseMail {

    private $incorrect = array();

    function __construct() {

        if (isset($_POST)) {
            foreach ($_POST as $k => $v) {
                $_POST[$k] = is_string($_POST[$k]) ? trim(strip_tags($_POST[$k])) : '';
            }
        }

        $message = $this->createMessage();

        if ($message) {
            $name = (isset($_POST['name']) && strlen($_POST['name']) ? $_POST['name'] : 'Adresat nieznany');
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers.= 'Content-Type: text/plain; charset=UTF-8' . "\r\n";
            $headers.= 'Reply-To: ' . $_POST['mail'] . '' . "\r\n";
            $headers.= 'Return-Path: ' . $_POST['mail'] . '' . "\r\n";
            $headers.= 'From: ' . $name . '<' . $_POST['mail'] . '>' . "\r\n";

            if (mail('kontakt@smefju.pl', 'Wiadomośc z strony internetowej', $message, $headers)) {
                $this->json = array(
                    'notice' => 'Wiadomość została wysłana.',
                    'success' => 'pass',
                    'option' => '<p>Dziękujemy za skorzystanie z formularza, Twoja wiadomość została wysłana.</p>'
                );
            }
        } else {
            $this->incorrect = implode(', ', $this->incorrect);
            if (isset($_POST['homeForm']) && $_POST['homeForm'] == 'true') {
                $this->json = array(
                    'header' => 'Proszę uzupełnić wymagane pola.',
                    'success' => 'fail',
                    'msg' => '<p><strong>W formularzu  pola</strong>: ' . $this->incorrect . ' są wymagane, prosimy o ich uzupełnienie.<p><strong>Czy chcesz <span onclick="resetForm();">spróbować jeszcze raz?</span></strong></p></p>'
                );
            } else {
                $this->json = array(
                    'header' => 'Proszę uzupełnić wymagane pola.',
                    'success' => 'fail',
                    'msg' => '<p><strong>W formularzu  pola</strong>: ' . $this->incorrect . ' są wymagane, prosimy o ich uzupełnienie.<p><strong>Czy chcesz <span onclick="resetForm();">spróbować jeszcze raz?</span></strong></p></p>'
                );
            }
        }
        New json($this->json);
    }

    /**
     * @return boolean|string w przypadku podania błędnych danych bądź niewypełnienia wymaganych pól funkcja zwróci false. W przeciwnym przypadku funkcja zwróci wiadomość razem z nagłówkami
     */
    function createMessage() {
        if (!isset($_POST['mail']) || !preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', $_POST['mail']))
            $this->incorrect[] = '<i>e-mail</i>';
        if (!isset($_POST['message']) || !strlen($_POST['message']))
            $this->incorrect[] = '<i>wiadomość</i>';

        if (empty($this->incorrect)) {
            $message = '';
            if (isset($_POST['name']) && strlen($_POST['name']))
                $message.= 'Imię i nazwisko: ' . $_POST['name'] . '' . "\n";
            $message.= 'Adres e-mail: ' . $_POST['mail'] . '' . "\n";
            $message.= 'Treść wiadomości: ' . $_POST['message'] . "\n";
        } else {
            return false;
        }
    }

}

new parseMail;