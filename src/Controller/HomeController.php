<?php

namespace App\Controller;
use App\Entity\Formateur;
use App\Entity\Societe;
use App\Entity\Departement;
use App\Entity\DomainFormation;

use App\Notification\InscriptionNotification;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeController extends AbstractController
{
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

	/**
     * @Route("/", name="index")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dbDepartement = $entityManager->getRepository(Departement::class)->findAll();

        $domains = $entityManager->getRepository(DomainFormation::class)->findBy(array(),array('number' => 'ASC'));
        return $this->render('home/index.html.twig', array( 'departements' => $dbDepartement, 'domains' => $domains,'home' => 'home'));
    }
    /**
     * @Route("/home", name="home")
     */
    public function home(InscriptionNotification $inscriptionNotification)
    {
        $entityManager = $this->getDoctrine()->getManager();
            $dbDepartement = $entityManager->getRepository(Departement::class)->findAll();

            $domains = $entityManager->getRepository(DomainFormation::class)->findBy(array(),array('number' => 'ASC'));

        return $this->render('home/index.html.twig', array( 'departements' => $dbDepartement, 'domains' => $domains,'home' => 'home'));
    }

    /**
     * @Route("/connexionOrganisme", name="connexionOrganisme", methods="POST")
     */
    public function connexionOrganisme(request $request)
    {
        $email = $request->get("email");
        $password = $request->get("password");


        $organisme = $this->getDoctrine()->getRepository(Societe::class)
                         ->findOneBy(['email' => $email]);

         if (is_null($organisme)) {
               return $this->redirectToRoute('echec-connexion-organisme');
            }
        if (($this->decrypt($organisme->getPassword()) == $password) && ($organisme->getActiveEmail() == 1)) {
            $session = new Session();
            $session->start();
            $organisme->setLastLogin();
            $session->set('organisme', $organisme);

           
            if ($session->get('organisme')) {
                return $this->redirectToRoute('organisme-profile');
            }
        } else if ($this->decrypt($organisme->getPassword()) == $password && $organisme->getActiveEmail() == 0) {
            return $this->redirectToRoute('echec-activation-organisme');
        } else {
            return $this->redirectToRoute('echec-connexion-organisme');
        }
        
        
    }
    /**
     * @Route("/echecConnexionOrganisme", name="echec-connexion-organisme")
     */
    public function echecConnexionOrganisme()
    {
        return $this->render('home/index.html.twig', ['echecConnexionOrganisme' => 'echec', 'home' => 'home']);
    }

    /**
     * @Route("/echecActivationOrganisme", name="echec-activation-organisme")
     */
    public function echecActivationOrganisme()
    {
        return $this->render('home/index.html.twig', ['echecActivationEmailOrganisme' => 'email', 'home' => 'home']);
        
    }

    /**
     * @Route("/connexionFormateur", name="connexionFormateur", methods="POST")
     */
    public function connexionFormateur(request $request)
    {
        $email = $request->get("email");
        $password = $request->get("password");


        $formateur = $this->getDoctrine()->getRepository(Formateur::class)
                         ->findOneBy(['email' => $email]);
        if (is_null($formateur)) {
           return $this->redirectToRoute('echec-connexion-formateur');
        }
        if (($this->decrypt($formateur->getPassword()) == $password) && ($formateur->getActiveEmail() == 1)) {
            $session = new Session();
            $session->start();

            $formateur->setLastLogin();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formateur);
            $entityManager->flush();
            $session->set('formateur', $formateur);


            if ($session->get('formateur')) {
                return $this->redirectToRoute('formateur-profile');
            }
        } else if ($this->decrypt($formateur->getPassword()) == $password && $formateur->getActiveEmail() == 0) {
            return $this->redirectToRoute('echec-activation-formateur');
        } else {
            return $this->redirectToRoute('echec-connexion-formateur');
        }
        
        
    }
    /**
     * @Route("/echecConnexionFormateur", name="echec-connexion-formateur")
     */
    public function echecConnexionFormateur()
    {
        return $this->render('home/index.html.twig', ['echecConnexionFormateur' => 'echec', 'home' => 'home']);
        
    }

    /**
     * @Route("/echecActivationFormateur", name="echec-activation-formateur")
     */
    public function echecActivationFormateur()
    {
        return $this->render('home/index.html.twig', ['echecActivationEmailFormateur' => 'email', 'home' => 'home']);
        
    }

    /**
     * @Route("/inscription-formateur", name="inscription-formateur", methods="POST")
     */
    public function inscriptionFormateur(request $request, InscriptionNotification $inscriptionNotification)
    {
        $civilite = $request->get("civilite");
        $nom = $request->get("nom");
        $prenom = $request->get("prenom");
        $adresse = $request->get("adresse");
        $adresse2 = $request->get("adresse2");
        $codePostal = $request->get("codePostal");
        $ville = $request->get("ville");
        $fix = $request->get("fix");
        $mobile = $request->get("mobile");
        $email = $request->get("email");


        $password = $request->get("password");
        
        $password_hashed = $this->encrypt($password);
        $nbrEmail = $this->getDoctrine()->getRepository(Formateur::class)
                         ->count(['email' => $email]);

        if ($nbrEmail === 1) {
            return $this->redirectToRoute('echec-inscription-formateur');
        }
        

        $formateur = new Formateur();

        $formateur->setCivilite($civilite);
        $formateur->setNom($nom);
        $formateur->setPrenom($prenom);
        $formateur->setAdresse1($adresse);
        $formateur->setAdresse2($adresse2);
        $formateur->setCodePostal($codePostal);
        $formateur->setVille($ville);
        $formateur->setFix($fix);
        $formateur->setMobile($mobile);
        $formateur->setEmail($email);
        $formateur->setPassword($password_hashed);
        $formateur->setActiveAdmin(1);

        

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($formateur);
        $entityManager->flush();


        // $formateur->setNom(encrypt($formateur->getNom()));
        $formateur = $this->getDoctrine()->getRepository(Formateur::class)
                         ->findOneBy(['email' => $email]);
        $inscriptionNotification->notifyFormateur($formateur);


        
        return $this->redirectToRoute('inscription-reussite');
        
    }

    /**
     * @Route("/echecInscriptionFormateur", name="echec-inscription-formateur")
     */
    public function echecInscriptionFormateur()
    {
        return $this->render('home/index.html.twig', ['echecInscriptionFormateur' => 'echec', 'home' => 'home']);
    }

    /**
     * @Route("/inscription-organisme", name="inscription-organisme", methods="POST")
     */
    public function inscriptionOrganisme(request $request, InscriptionNotification $inscriptionNotification)
    {
        $civilite = $request->get("civilite");
        $nom = $request->get("nom");
        $prenom = $request->get("prenom");
        $raisonSocial = $request->get("raisonSocial");
        $adresse = $request->get("adresse");
        $adresse2 = $request->get("adresse2");
        $codePostal = $request->get("codePostal");
        $ville = $request->get("ville");
        $fix = $request->get("fix");
        $mobile = $request->get("mobile");
        $email = $request->get("email");


        $password = $request->get("password");

        $password_hashed = $this->encrypt($password);
        $nbrEmail = $this->getDoctrine()->getRepository(Societe::class)
                         ->count(['email' => $email]);

        if ($nbrEmail === 1) {
            return $this->redirectToRoute('echec-inscription-organisme');
        }
        

        $organisme = new Societe();

        $organisme->setCivilite($civilite);
        $organisme->setNom($nom);
        $organisme->setPrenom($prenom);
        $organisme->setRaisonSocial($raisonSocial);
        $organisme->setAdresse1($adresse);
        $organisme->setAdresse2($adresse2);
        $organisme->setCodePostal($codePostal);
        $organisme->setVille($ville);
        $organisme->setFix($fix);
        $organisme->setMobile($mobile);
        $organisme->setEmail($email);
        $organisme->setPassword($password_hashed);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($organisme);
        $entityManager->flush();

        $organisme = $this->getDoctrine()->getRepository(Societe::class)
                         ->findOneBy(['email' => $email]);
        $inscriptionNotification->notifyOrganisme($organisme);

        
        return $this->redirectToRoute('inscription-reussite');
        
    }

    /**
     * @Route("/echecInscriptionOrganisme", name="echec-inscription-organisme")
     */
    public function echecInscriptionOrganisme()
    {
        return $this->render('home/index.html.twig', ['echecInscriptionOrganisme' => 'echec', 'home' => 'home']);
    }

    /**
     * @Route("/inscriptionReussite", name="inscription-reussite")
     */
    public function inscriptionReussite()
    {
        return $this->render('home/index.html.twig', ['inscriptionReussite' => 'success', 'home' => 'home']);
    }

    /**
     * @Route("/activation/compte/formateur/{id}", name="activation-account-formateur")
     */
    public function activationCompteFormateur($id)
    {
        $formateur = $this->getDoctrine()->getRepository(Formateur::class)
                         ->findOneBy(['id' => $this->decrypt($id)]);
        $formateur->setActiveEmail(1);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($formateur);
        $entityManager->flush();

        $session = new Session();
        $session->start();
        $formateur->setLastLogin();
        $session->set('formateur', $formateur);

        return $this->redirectToRoute('index');
    }
    /**
     * @Route("/activation/compte/organisme/{id}", name="activation-account-organisme")
     */
    public function activationCompteOrganisme($id)
    {
        $organisme = $this->getDoctrine()->getRepository(Societe::class)
                         ->findOneBy(['id' => $this->decrypt($id)]);
        $organisme->setActiveEmail(1);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($organisme);
        $entityManager->flush();

        $session = new Session();
        $session->start();
        $organisme->setLastLogin();
        $session->set('organisme', $organisme);

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {   
        $session = new Session();
        if ($session->get('formateur') || $session->get('organisme')) {
            $session->clear();

            return $this->redirectToRoute('index');
        } else {
            return $this->redirectToRoute('index');
        }   
    }
}
