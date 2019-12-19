<?php

namespace App\Controller;
use App\Entity\Societe;
use App\Entity\Formateur;
use App\Entity\FormationFormateur;
use App\Entity\Departement;
use App\Entity\Disponibilite;
use App\Entity\DomainFormation;
use App\Entity\Reservation;
use App\Entity\Notification;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class OrganismeController extends AbstractController
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
     * @Route("/organisme-profile", name="organisme-profile")
     */
    public function profile(Request $request)
    {
    	$session = $request->getSession();
    	if ($session->get('organisme')) {
    		$entityManager = $this->getDoctrine()->getManager();
    		$organisme = $entityManager->getRepository(Societe::class)->findOneBy([ 'id' => $session->get('organisme')->getId()]);

    		$dbDepartement = $entityManager->getRepository(Departement::class)->findAll();

    		$domains = $entityManager->getRepository(DomainFormation::class)->findBy(array(),array('number' => 'ASC'));

    		foreach ($organisme->getNotifications() as $notif) {
	        	if ($notif->getNumber() < 4) {
	        		$notif->setNumber($notif->getNumber()+1);
	        		if ($notif->getNumber() == 3) {
	        			$notif->setVue(1);
	        		}
	        	}
	        }

    		$form = $this->createFormBuilder()
		        ->add('logo', FileType::class, ['label' => false, 'required' => false])
		        ->getForm();
	        
	        $fileSystem = new Filesystem();
	        $form->handleRequest($request);
	        if ($form->isSubmitted()) {
	        	$data = $form->getData();

	            if ($data['logo']) {
	            	if ($organisme->getLogo()) {
	            		try {
		                $fileSystem->remove($this->getParameter('images_directory').$organisme->getLogo());
		                } catch (IOExceptionInterface $exception) {
		                    echo "An error occurred while deleting your picture at ".$exception->getPath();
		                }
	            	}
	                
	                $file = $data['logo'];
	                $fileName = md5(uniqid()).'.'.$file->guessExtension();
	                $file->move(
	                            $this->getParameter('images_directory').'/organisme/profile/logo/',
	                            $fileName
	                        );
	                $organisme->setLogo('/organisme/profile/logo/'.$fileName);
	            }



		        $nom = $request->get("nom");
		        $prenom = $request->get("prenom");
		        $raisonSocial = $request->get("raisonSocial");
		        $adresse = $request->get("adresse");
		        $adresse2 = $request->get("adresse2");
		        $codePostal = $request->get("codePostal");
		        $ville = $request->get("ville");
		        $fix = $request->get("fix");
		        $mobile = $request->get("mobile");

		        $organisme->setNom($nom);
		        $organisme->setPrenom($prenom);
		        $organisme->setRaisonSocial($raisonSocial);
		        $organisme->setAdresse1($adresse);
		        $organisme->setAdresse2($adresse2);
		        $organisme->setCodePostal($codePostal);
		        $organisme->setVille($ville);
		        $organisme->setFix($fix);
		        $organisme->setMobile($mobile);



		        $entityManager = $this->getDoctrine()->getManager();
		        $entityManager->persist($organisme);
		        $entityManager->flush();
		        $session->set('organisme', $organisme);
		        return $this->redirectToRoute('organisme-profile');
	         }
	         $session->set('organisme', $organisme);
       		 return $this->render('organisme/index.html.twig', array('form' => $form->createView(), 'organisme' => $organisme, 'departements' => $dbDepartement, 'profile' => 'profile', 'domains' => $domains));
       	}else {
       		return $this->redirectToRoute('index');
       	}
    }

    /**
     * @Route("/recherche-formateur", name="recherche-formateur", methods={"POST", "GET"})
     */
    public function chercher( Request $request)
    {
    	$session = $request->getSession();
    	
    		$entityManager = $this->getDoctrine()->getManager();
	        $formateurs = $entityManager->getRepository(Formateur::class)->findAll();
		    $dbDepartement = $entityManager->getRepository(Departement::class)->findAll();

		    $domains = $entityManager->getRepository(DomainFormation::class)->findBy(array(),array('number' => 'ASC'));

		    $search = array();
		    $formationDisponible = array();
		    $search_departement = $request->get('search-departement');
		    $search_date = explode(',', $request->get('search-date'));
		    $search_domain =  $request->get('search-formation');
		    foreach ($formateurs as $formateur) {
		    	$test = true;
		    	$departementDispo = explode(', ', $formateur->getDepartementDisponibilite());
		    	$dateDispo = explode(',', $formateur->getDateDisponibilite());
		    	$formationDispo = $formateur->getDisponibilites();
		    	foreach ($formationDispo as $dispo) {
		    		array_push($formationDisponible, $dispo->getFormation());
		    	}
		    	foreach ($search_date as $sd) {
		    		if (in_array($sd, $dateDispo)) {
		    			$test = false;
		    		}
		    	}
		    	if ( ( in_array($search_departement, $departementDispo) || $formateur->getDepartementDisponibilite() == "all") && ( $test ) && (in_array($search_domain, $formationDisponible)) && ($formateur->getActiveAdmin() == 1) ) {
		    		$formateur->setId($this->encrypt($formateur->getId()));
		    		array_push($search, $formateur);

		    	}
		    }
	    	
	    if ($session->get('organisme')) {
    		if ($session->get('organisme')->getActiveAdmin() == 1) {
    			return $this->render('organisme/chercher.html.twig', array('search' => $search, 'departements' => $dbDepartement, 'domains' => $domains, 'nomFormation' => $search_domain, 'nomDepartement' => $request->get('search-departement'), 'dateReservation' => $request->get('search-date')));
    		}else{
    			return $this->render('organisme/error-chercher.html.twig', array('erroractivation' => count($search), 'departements' => $dbDepartement, 'domains' => $domains));
    		}
    	}else {
       		return $this->render('organisme/error-chercher.html.twig', array('errorlogin' => count($search), 'departements' => $dbDepartement, 'domains' => $domains));
       	}
    	
    }

    /**
     * @Route("/reservation-session", name="reservation-session", methods={"POST"})
     */
    public function reservation(Request $request)
    {
    	$session = $request->getSession();
    	if ($session->get('organisme')) {
    		$id_formateur = $this->decrypt($request->get('id_formateur'));
    		$entityManager = $this->getDoctrine()->getManager();
	        $formateur = $entityManager->getRepository(Formateur::class)->find($id_formateur);
	        $organisme = $entityManager->getRepository(Societe::class)->find($request->get('id_organisme'));
			
			$reservation = new Reservation();
			$notification = new Notification();
			$notification->setName('reservation');
			$notification->setFormateur($formateur);
			$notification->setSociete($organisme);
			$notification->setMessage('Vous avez une nouvelle réservation de la part de la societé '.$organisme->getRaisonSocial());

			$dateReservationFormateur = $formateur->getDateDisponibilite().','.$request->get('dateReservation');
			$formateur->setDateDisponibilite($dateReservationFormateur);

			$reservation->setFormateur($formateur);
			$reservation->setSociete($organisme);
			$reservation->setDateCreation();
			$reservation->setDateReservation($request->get('dateReservation'));
			$nomFormation = $request->get('nomsFormations');
			$reservation->setFormation($nomFormation);
			$reservation->setNbrJour($request->get('nbrJour'));
			$reservation->setDepartement($request->get('nomDepartement'));

			$entityManager = $this->getDoctrine()->getManager();
	        $entityManager->persist($reservation);
	        $entityManager->persist($formateur);
	        $entityManager->persist($notification);
	        $entityManager->flush();

	    	return $this->redirectToRoute('organisme-profile');
    	}else {
       		return $this->redirectToRoute('index');
       	}
    	
    }

    /**
     * @Route("/profile-formateur/{id}", name="profile-formateur", methods={"GET"})
     */
    public function view_profile($id, Request $request)
    {
    	$session = $request->getSession();
    	if ($session->get('organisme')) {
    		$id = $this->decrypt($id);
    		$entityManager = $this->getDoctrine()->getManager();
	        $formateur = $entityManager->getRepository(Formateur::class)->find($id);
	    	return $this->render('formateur/index.html.twig', array('formateur' => $formateur));
    	}else {
       		return $this->redirectToRoute('index');
       	}
    	
    }

    /**
     * @Route("/valider-reservation/{id}", name="valider-reservation", methods={"GET"})
     */
    public function validation($id, Request $request)
    {
    	$session = $request->getSession();
    	if ($session->get('organisme')) {
    		$entityManager = $this->getDoctrine()->getManager();
	        $reservation = $entityManager->getRepository(Reservation::class)->find($id);
	        $formateur = $reservation->getFormateur();
	        $notification = new Notification();

	        $notification->setName('validation');
	        $notification->setMessage('Votre réservation avec la societé '.$reservation->getSociete()->getRaisonSocial().' sous la formation '.$reservation->getFormation().' a été valider, veuillez remplire les avis');
	        $notification->setFormateur($formateur);

	        $reservation->setStatus(1);
	        $entityManager->persist($reservation);
	        $entityManager->persist($notification);
	        $entityManager->flush();

	    	return $this->redirectToRoute('organisme-profile');
    	}else {
       		return $this->redirectToRoute('index');
       	}
    	
    }

}
