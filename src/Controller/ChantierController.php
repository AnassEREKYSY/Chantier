<?php

namespace App\Controller;


use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Chantier;
use App\Entity\Interlocuteur;
use App\Entity\Historique;
use App\Repository\ChantierRepository;
use App\Repository\InterlocuteurRepository;
use App\Repository\HistoriqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;

class ChantierController extends AbstractController
{

    #[Route('/chantier', name: 'app_base')]
    public function index(): Response
    {
        return $this->render('chantier/home.html.twig');
    }
    #[Route('/chantier/interlocuteur', name: 'app_interlocuteur')]
    public function interlocuteur(InterlocuteurRepository $interlocuteurRepository): Response
    {
        $inter=$interlocuteurRepository->findAll();
        return $this->render('chantier/interlocuteur.html.twig', ['inters'=>$inter]);
    }
    #[Route('/chantier/chantier', name: 'app_chantier')]
    public function chantier(ChantierRepository $chantierRepository): Response
    {
        $chantier=$chantierRepository->findAll();
        return $this->render('chantier/chantier.html.twig', ['chantiers'=>$chantier]);
    }


    #[Route('/chantier/add/{id}', name: 'app_add')]
    public function add(ChantierRepository $chantierRepository
                        , InterlocuteurRepository $interlocuteurRepository
                        , Request $request,EntityManagerInterface $manager): Response
    {
        $formCreation=null;
        $type=null;
        if(strcmp($request->attributes->get('id'),"chantier")==0){
            $chantier=new Chantier();
            $form=$this->createFormBuilder($chantier)
                ->add('description', TextareaType::class)
                ->add('datedebut',DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Date',
                    'attr' => ['class' => 'datepicker'],
                ])
                ->add('datefin',DateType::class,[
                    'widget' => 'single_text',
                    'label' => 'Date',
                    'attr' => ['class' => 'datepicker'],
                ])
                ->add('type')
                ->add('statut')
                ->getForm();
            $form->handleRequest($request);
            $formCreation=$form;
            $type="chantier";
            if($form->isSubmitted() && $form->isValid()){
                $manager->persist($chantier);
                $manager->flush();
                return $this->redirectToRoute('app_chantier');
            }
        }else{
            $inter=new Interlocuteur();
            $form=$this->createFormBuilder($inter)
                ->add('nom',)
                ->add('prenom',)
                ->add('fonction',)
                ->add('telephone')
                ->getForm();
            $form->handleRequest($request);
            $formCreation=$form;
            $type="inter";
            if($form->isSubmitted() && $form->isValid()){
                $manager->persist($inter);
                $manager->flush();
                return $this->redirectToRoute('app_interlocuteur');
            }
        }
        return $this->render('chantier/create.html.twig', ['formCreation'=>$formCreation->createView(),'type'=>$type]);
    }
    #[Route('/chantier/searche/{id}', name: 'app_searche')]
    public function searche(ChantierRepository $chantierRepository
                            , InterlocuteurRepository $interlocuteurRepository
                            , Request $request,EntityManagerInterface $manager): Response
    {
        $chantier=$chantierRepository->findAll();
        return $this->render('chantier/chantier.html.twig', ['chantiers'=>$chantier]);
    }
    #[Route('/chantier/update/{id}', name: 'app_update')]
    public function update(ChantierRepository $chantierRepository
                            , InterlocuteurRepository $interlocuteurRepository
                            , Request $request, EntityManagerInterface $manager): Response
    {
        $chantier=$chantierRepository->findAll();
        return $this->render('chantier/chantier.html.twig', ['chantiers'=>$chantier]);
    }
    #[Route('/chantier/delete/{id}', name: 'app_delete')]
    public function delete(ChantierRepository $chantierRepository
                            , InterlocuteurRepository $interlocuteurRepository
                            , Request $request, EntityManagerInterface $manager): Response
    {
        $chantier=$chantierRepository->findAll();
        return $this->render('chantier/chantier.html.twig', ['chantiers'=>$chantier]);
    }


}
