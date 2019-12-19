<?php

namespace App\Controller;
use App\Entity\Formateur;
use App\Entity\FormationFormateur;
use App\Entity\Departement;
use App\Entity\Disponibilite;
use App\Entity\DomainFormation;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class FormateurController extends AbstractController
{
    /**
     * @Route("/formateur-profile", name="formateur-profile", methods={"GET","HEAD","POST"})
     */
    public function profile(Request $request)
    {
    	$session = $request->getSession();
    	if ($session->get('formateur')) {

    		$entityManager = $this->getDoctrine()->getManager();
	        $formateur = $entityManager->getRepository(Formateur::class)->findOneBy([ 'id' => $session->get('formateur')->getId()]);

	        $dbDepartement = $entityManager->getRepository(Departement::class)->findAll();
	        $domains = $entityManager->getRepository(DomainFormation::class)->findBy(array(),array('number' => 'ASC'));

	        $upload = new Formateur();
	        $formation = new FormationFormateur();
	        $disponibilite = new Disponibilite();

	        foreach ($formateur->getNotifications() as $notif) {
	        	if ($notif->getNumber() < 4) {
	        		$notif->setNumber($notif->getNumber()+1);
	        		if ($notif->getNumber() == 3) {
	        			$notif->setVue(1);
	        		}
	        	}
	        }

	        // $form = $this->createForm(FormateurType::class, $upload);
	        $form = $this->createFormBuilder()
		        ->add('cv', FileType::class, ['label' => false, 'required' => false])
		        ->add('photo', FileType::class, ['label' => false, 'required' => false])
		        ->getForm();
	        
	        $fileSystem = new Filesystem();
	        $form->handleRequest($request);
	        if ($form->isSubmitted()) {
	        	$data = $form->getData();
	            if ($data['cv']) {
	            	if ($formateur->getCv()) {
	            		try {
		                $fileSystem->remove($this->getParameter('images_directory').$formateur->getCv());
		                } catch (IOExceptionInterface $exception) {
		                    echo "An error occurred while deleting your picture at ".$exception->getPath();
		                }
	            	}
	                
	                $file = $data['cv'];
	                $fileName = md5(uniqid()).'.'.$file->guessExtension();
	                $file->move(
	                            $this->getParameter('images_directory').'/formateur/profile/cv/',
	                            $fileName
	                        );
	                $formateur->setCv('/formateur/profile/cv/'.$fileName);
	            }

	            if ($data['photo']) {
	            	if ($formateur->getPhoto()) {
	            		try {
		                $fileSystem->remove($this->getParameter('images_directory').$formateur->getPhoto());
		                } catch (IOExceptionInterface $exception) {
		                    echo "An error occurred while deleting your picture at ".$exception->getPath();
		                }
	            	}
	                
	                $file = $data['photo'];
	                $fileName = md5(uniqid()).'.'.$file->guessExtension();
	                $file->move(
	                            $this->getParameter('images_directory').'/formateur/profile/photo/',
	                            $fileName
	                        );
	                $formateur->setPhoto('/formateur/profile/photo/'.$fileName);
	            }



		        $nom = $request->get("nom");
		        $prenom = $request->get("prenom");
		        $adresse = $request->get("adresse");
		        $adresse2 = $request->get("adresse2");
		        $codePostal = $request->get("codePostal");
		        $ville = $request->get("ville");
		        $fix = $request->get("fix");
		        $mobile = $request->get("mobile");

		        $formateur->setNom($nom);
		        $formateur->setPrenom($prenom);
		        $formateur->setAdresse1($adresse);
		        $formateur->setAdresse2($adresse2);
		        $formateur->setCodePostal($codePostal);
		        $formateur->setVille($ville);
		        $formateur->setFix($fix);
		        $formateur->setMobile($mobile);



		        $entityManager = $this->getDoctrine()->getManager();
		        $entityManager->persist($formateur);
		        $entityManager->flush();
		        $session->set('formateur', $formateur);
		        return $this->redirectToRoute('formateur-profile');
	         }				

	         $formExp = $this->get('form.factory')->createNamedBuilder('experience')
		        ->add('attestation', FileType::class, ['label' => false, 'required' => false])
		        ->add('save', SubmitType::class, ['label' => 'Ajouter l\'Experience'])
		        ->getForm();

		    $formExp->handleRequest($request);
	        if ($formExp->isSubmitted()) {
	        	$data = $formExp->getData();
	            if ($data['attestation']) {
	            	// if ($formateur->getCv()) {
	            	// 	try {
		            //     $fileSystem->remove($this->getParameter('images_directory').$formateur->getCv());
		            //     } catch (IOExceptionInterface $exception) {
		            //         echo "An error occurred while deleting your picture at ".$exception->getPath();
		            //     }
	            	// }
	                
	                $file = $data['attestation'];
	                $fileName = md5(uniqid()).'.'.$file->guessExtension();
	                $file->move(
	                            $this->getParameter('images_directory').'/formateur/profile/attestation/'.$session->get('formateur')->getId().'/',
	                            $fileName
	                        );
	                $formation->setAttestation('/formateur/profile/attestation/'.$session->get('formateur')->getId().'/'.$fileName);
	            }

		        $nom = $request->get("nomFormation");
		        $date = $request->get("date");
		        $description = $request->get("description");

		        $formation->setNom($nom);
		        $formation->setDate($date);
		        $formation->setDescription($description);
		        $formation->setFormateur($formateur);



		        $entityManager = $this->getDoctrine()->getManager();
		        $entityManager->persist($formation);
		        $entityManager->flush();
		        $session->set('formateur', $formateur);
		        return $this->redirectToRoute('formateur-profile');
	         }

	        
	        $formDateDisp = $this->get('form.factory')->createNamedBuilder('dateDisponibilites')
		        ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
		        ->getForm();

		    $formDateDisp->handleRequest($request);
	        if ($formDateDisp->isSubmitted()) {
	        	$data = $formDateDisp->getData();

		        $dateDisponibilite = $request->get("dateDisponibilite");

		        $formateur->setDateDisponibilite($dateDisponibilite);



		        $entityManager = $this->getDoctrine()->getManager();
		        $entityManager->persist($formateur);
		        $entityManager->flush();
		        $session->set('formateur', $formateur);
		        return $this->redirectToRoute('formateur-profile');
	         }

	        $formFrais = $this->get('form.factory')->createNamedBuilder('FraisAnnexe')
		        ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
		        ->getForm();

	        $formFrais->handleRequest($request);
	        if ($formFrais->isSubmitted()) {
	        	$data = $formFrais->getData();

		        $fraisRepas = $request->get("fraisRepas");
		        $fraisHotel = $request->get("fraisHotel");
		        $fraisDeplacement = $request->get("fraisDeplacement");

		        $formateur->setFraisRepas($fraisRepas);
		        $formateur->setFraisHotel($fraisHotel);
		        $formateur->setFraisDeplacement($fraisDeplacement);



		        $entityManager = $this->getDoctrine()->getManager();
		        $entityManager->persist($formateur);
		        $entityManager->flush();
		        $session->set('formateur', $formateur);
		        return $this->redirectToRoute('formateur-profile');
	         }


	         $formDepart = $this->get('form.factory')->createNamedBuilder('departementsDispo')
		        ->add('save', SubmitType::class, ['label' => 'Editer Disponibilité'])
		        ->getForm();

		    $formDepart->handleRequest($request);
	        if ($formDepart->isSubmitted()) {
	        	$data = $formDepart->getData();

		        $departement = $request->get("departement");

		        $formateur->setDepartementDisponibilite($departement);

		        $entityManager = $this->getDoctrine()->getManager();
		        $entityManager->persist($formateur);
		        $entityManager->flush();
		        $session->set('formateur', $formateur);
		        return $this->redirectToRoute('formateur-profile');
	         }


	         $formFrm = $this->get('form.factory')->createNamedBuilder('formation')
		        ->add('save', SubmitType::class, ['label' => 'Ajouter la Formation'])
		        ->getForm();

		    $formFrm->handleRequest($request);
	        if ($formFrm->isSubmitted()) {
	        	$data = $formFrm->getData();

		        $format = $request->get("formationDisponible");
		        $cout = $request->get("cout");


		        $disponibilite->setFormation($format);
		        $disponibilite->setCoutJour($cout);
		        $disponibilite->setFormateur($formateur);



		        $entityManager = $this->getDoctrine()->getManager();
		        $entityManager->persist($disponibilite);
		        $entityManager->flush();
		        $session->set('formateur', $formateur);
		        return $this->redirectToRoute('formateur-profile');
	         }

	         $entityManager = $this->getDoctrine()->getManager();
		        $entityManager->persist($formateur);
		        $entityManager->flush();
	       	return $this->render('formateur/index.html.twig', array('form' => $form->createView(), 'formExp' => $formExp->createView(), 'formDateDisp' => $formDateDisp->createView(), 'formDepart' => $formDepart->createView(), 'formFrais' => $formFrais->createView(), 'formFrm' => $formFrm->createView(), 'formateur' => $formateur, 'dbDepartement' => $dbDepartement, 'domains' => $domains, 'profile' => 'profile'));
       	}else {
       		return $this->redirectToRoute('index');
       	}
    }

    /**
     * @Route("/formateur-profile/delete/experience/{id}", name="delete-experience", methods={"GET"})
     */
    public function delete_experience($id, Request $request)
    {
    	$session = $request->getSession();
    	if ($session->get('formateur')) {
            $entityManager = $this->getDoctrine()->getManager();
            $experience = $entityManager->getRepository(FormationFormateur::class)->find($id);
            $entityManager->remove($experience);
            $entityManager->flush();

            return $this->redirectToRoute('formateur-profile');
        } else {
            return $this->redirectToRoute('index');
        } 
    }

    /**
     * @Route("/formateur-profile/delete/formation/{id}", name="delete-formation", methods={"GET"})
     */
    public function delete_formation($id, Request $request)
    {
    	$session = $request->getSession();
    	if ($session->get('formateur')) {
            $entityManager = $this->getDoctrine()->getManager();
            $formation = $entityManager->getRepository(Disponibilite::class)->find($id);
            $entityManager->remove($formation);
            $entityManager->flush();

            return $this->redirectToRoute('formateur-profile');
        } else {
            return $this->redirectToRoute('index');
        } 
    }
}
