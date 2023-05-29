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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Annotation\Route;

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
    #[Route('/chantier/edit/{type}/{id}', name: 'app_edit')]
    public function add(ChantierRepository $chantierRepository
                        , InterlocuteurRepository $interlocuteurRepository
                        , Request $request,EntityManagerInterface $manager,
                            Chantier $chantier=null,Interlocuteur $inter=null): Response
    {
        $formCreation=null;
        $type=null;
        $etatButton=1;
        if(strcmp($request->attributes->get('id'),"chantier")==0 ||
            strcmp($request->attributes->get('type'),"chantier")==0){
            if(!$chantier){
                $chantier=new Chantier();
                $etatButton=0;
            }
            $form=$this->createFormBuilder($chantier)
                ->add('nomchantier')
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
        }else if(strcmp($request->attributes->get('id'),"inter")==0 ||
                strcmp($request->attributes->get('type'),"inter")==0){
            if(!$inter){
                $inter=new Interlocuteur();
                $etatButton=0;
            }
            $form=$this->createFormBuilder($inter)
                ->add('nom')
                ->add('prenom')
                ->add('fonction')
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
        }else{
            $form=$this->createFormBuilder()
                ->add('nom')
                ->add('prenom')
                ->add('chantier')
                ->getForm();
            $form->handleRequest($request);
            $formCreation=$form;
            $type="affectation";
            if ($form->isSubmitted() && $form->isValid()) {
                $form=$request->request->all();
                $interAffect=$interlocuteurRepository->findOneBy(['nom'=>$form['form']['nom']]);
                $chantierAffect=$chantierRepository->findOneBy(['nomChantier'=>$form['form']['chantier']]);
                if($chantierAffect!=null && $interAffect!=null){
                    $interAffect->addChantier($chantierAffect);
                    $manager->flush();
                    return $this->redirectToRoute('app_affectation');
                }
            }
        }
        return $this->render('chantier/create.html.twig', ['formCreation'=>$formCreation->createView(),'type'=>$type,'etatButton'=>$etatButton]);
    }
    #[Route('/chantier/searche/{id}', name: 'app_searche')]
    public function searche(ChantierRepository $chantierRepository
                            , InterlocuteurRepository $interlocuteurRepository
                            , Request $request,EntityManagerInterface $manager): Response
    {
        $form=$this->createFormBuilder()
            ->add('champs')
            ->getForm();
        $form->handleRequest($request);
        $result=null;
        $type=null;
        $qb = $manager->createQueryBuilder();
        if($form->isSubmitted() && $form->isValid()) {
            if (strcmp($request->attributes->get('id'), "inter") == 0) {
                $qb->select('i')
                    ->from(Interlocuteur::class, 'i')
                    ->where($qb->expr()->like('LOWER(i.nom)', ':searchTerm1'))
                    ->orWhere($qb->expr()->like('LOWER(i.prenom)', ':searchTerm1'))
                    ->orWhere($qb->expr()->like('LOWER(i.fonction)', ':searchTerm1'))
                    ->setParameter('searchTerm1', '%' . strtolower($form->get('champs')->getData()) . '%');
                $result = $qb->getQuery()->getResult();
                $type = "interlocuteur";

                return $this->render('chantier/searche.html.twig', ['formSearche' => $form->createView(), 'result' => $result, 'type' => $type]);
            } else {
                $qb->select('c')
                    ->from(Chantier::class, 'c')
                    ->where($qb->expr()->like('LOWER(c.nomChantier)', ':searchTerm1'))
                    ->orWhere($qb->expr()->like('LOWER(c.description)', ':searchTerm1'))
                    ->orWhere($qb->expr()->like('LOWER(c.type)', ':searchTerm1'))
                    ->orWhere($qb->expr()->like('LOWER(c.statut)', ':searchTerm1'))
                    ->setParameter('searchTerm1', '%' . strtolower($form->get('champs')->getData()) . '%');
                $result = $qb->getQuery()->getResult();
                $type = "chantier";

                return $this->render('chantier/searche.html.twig', ['formSearche' => $form->createView(), 'result' => $result, 'type' => $type]);
            }
        }

        return $this->render('chantier/searche.html.twig' ,['result'=>$result,'formSearche'=>$form->createView(),'type'=>$type]);
    }

    #[Route('/chantier/affectation', name: 'app_affectation')]
    public function affectation(ChantierRepository $chantierRepository
                            , InterlocuteurRepository $interlocuteurRepository
                            , Request $request,EntityManagerInterface $manager
                            ): Response
    {
        $inters=$interlocuteurRepository->findAll();
        return $this->render('chantier/affectation.html.twig', ['inters'=>$inters]);
    }


    #[Route('/chantier/delete/{type}/{id?0}', name: 'app_delete')]
    public function delete(ChantierRepository $chantierRepository
                            , InterlocuteurRepository $interlocuteurRepository
                            , Request $request, EntityManagerInterface $manager
                            , Chantier $chantier=null, Interlocuteur $interlocuteur=null): Response
    {
        $reponse=null;
        if(strcmp($request->attributes->get('type'),"chantier")==0){
            $chantierRepository->remove($chantier,true);
            $reponse=$this->render('chantier/chantier.html.twig', ['chantiers'=>$chantierRepository->findAll()]);
        }else if(strcmp($request->attributes->get('type'),"inter")==0){
            $interlocuteurRepository->remove($interlocuteur,true);
            $reponse=$this->render('chantier/interlocuteur.html.twig', ['inters'=>$interlocuteurRepository->findAll()]);
        }else{
            $interDelete=$interlocuteurRepository->findOneBy(['id'=>$request->get('id')]);
            $chantierDelete=$chantierRepository->findOneBy(['nomChantier'=>$request->get('chantier')]);
            $interDelete->removeChantier($chantierDelete);
            $manager->flush();
            $reponse=$this->render('chantier/affectation.html.twig', ['inters'=>$interlocuteurRepository->findAll()]);
        }
        return $reponse;
    }
    #[Route('/chantier/historique', name: 'app_historique')]
    public function historique(InterlocuteurRepository $interlocuteurRepository): Response
    {
        $inter=$interlocuteurRepository->findAll();
        return $this->render('chantier/historique.html.twig', ['inters'=>$inter]);
    }

}
