<?php 

namespace App\Notification;
 
use App\Entity\Formateur;
use App\Entity\Societe;
use Twig\Environment;
 
class InscriptionNotification
{
 
    /**
     * @var \Swift_Mailer
     */
 
    private $mailer;
    /**
     * @var Environment
     */
 
    private $renderer;
 
    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
 
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

   public function encrypt($string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'fe67d68ee1e09b47acd8810b880d537034c10c15344433a992b9c79002666844';
        $secret_iv = 'fdd3345455fffgffffhkkyoife67d68ee1e09b47acd8810b880d537034c10c15344433a992b9c79002666844';

        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }

    public function decrypt($string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'fe67d68ee1e09b47acd8810b880d537034c10c15344433a992b9c79002666844';
        $secret_iv = 'fdd3345455fffgffffhkkyoife67d68ee1e09b47acd8810b880d537034c10c15344433a992b9c79002666844';

        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }
 
    public function notifyFormateur(Formateur $formateur)
    {
        $lien = "/activation/compte/formateur/".$this->encrypt($formateur->getId());
        $message = (new \Swift_Message('Activation de votre compte form connect'))
            ->setFrom(['form.connect1@gmail.com' => 'Form Connect'])
            ->setTo($formateur->getEmail())
            ->setBody($this->renderer->render('home/email-validation.html.twig', [
                'formateur' => $formateur, 'lienFormateur' =>$lien
            ]), 'text/html');
        $this->mailer->send($message);
    }

    public function notifyOrganisme(Societe $organisme)
    {
        $lien = "/activation/compte/organisme/".$this->encrypt($organisme->getId());
        $message = (new \Swift_Message('Activation de votre compte form connect'))
            ->setFrom(['form.connect1@gmail.com' => 'Form Connect'])
            ->setTo($organisme->getEmail())
            ->setBody($this->renderer->render('home/email-validation.html.twig', [
                'organisme' => $organisme, 'lienOrganisme' =>$lien
            ]), 'text/html');
        $this->mailer->send($message);
    }
}
 ?>