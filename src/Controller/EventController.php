<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Service\StatsService;
use App\Service\PaginationService;
use App\Repository\EventRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EventController extends AbstractController
{
    /**
     * Permet d'avoir accès à la liste des évènements
     * 
     * @Route("/events", name="event_index")
     */
    public function index(EventRepository $repo, EntityManagerInterface $manager, StatsService $statsService)
    {   

        $eventToCome = $statsService->getEvents('ASC');

        $numberOfEvents = $statsService->getCountEvents('ASC');
        // dump($numberOfEvents);
        // die();
        


        // $pagination->setEntityClass(Event::class)
        // ->setPage($page);

        // $event = new Event();
        // $form = $this->createForm(EventType::class, $event);
        // $form->handleRequest($request);
        // $evenement = $repo->evenementNonExpire($event);

        // $repo = $this->getDoctrine()->getRepository(Event::class);
        // $events = $repo->evenementsNonExpire();

        return $this->render('events/index.html.twig', [
            // 'pagination' => $pagination
            // 'events' => $events,
            'eventToCome'=> $eventToCome,
            'numberOfEvents' => $numberOfEvents
            // 'form' => form->createView()
        ]);
    }

    /**
     * Création d'un article dans le fil d'actualité
     * 
     * @Route("/events/new", name="events_new")
     * @Route("/events/{id}/edit", name="events_edit")
     * @IsGranted("ROLE_ADMIN")
     * 
     */
    public function form(Event $event = null, Request $request, EntityManagerInterface $manager)
    {
        
        $event = new Event();
        $user = $this->getUser();

        if(!$event){
            $event = new Event();
        }

        $form = $this->createFormBuilder($event)
                     ->add('name', TextType::class)
                     ->add('subtitle', TextType::class)
                     ->add('date', DateTimeType::class) 
                     ->add('place', TextType::class)
                     ->add('picture', UrlType::class)
                     ->add('description', TextareaType::class, [
                        'label' => 'Texte de l\'article',
                        'attr' => [
                        'label' => 'Description de l\évènement',
                        'rows' => '6',
                        'cols' =>  '20'
                    ] 
                ])
                    // ->add('name', TextType::class,  [
                    //     'label' => 'Nom de l\'évènement',
                    // ])
                    // ->add('subtitle', TextType::class,  [
                    //     'label' => 'Sous-titre de l\'évènement',
                    // ])
                    // ->add('date', DateTimeType::class,  [
                    //     'label' => 'Date de l\'évènement',
                    // ])
                    // ->add('place', TextType::class,  [
                    //     'label' => 'Lieu de l\'évènement',
                    // ])
                    // ->add('picture', UrlType::class,  [
                    //     'label' => 'URL de l\iImage d\'illustration',
                    // ])
                    // ->add('description', TextareaType::class,  [
                    //     'label' => 'Description de l\'évènement',
                    //     'attr' => [
                    //     'rows' => '10',
                    //     'cols' =>  '20'
                    //     ] 
                    // ])

                    ->getForm();

        //Ligne ajouté avec Bertrand pour appeller le formulaire que j'ai construit
        //$form = $this->createForm(ArticleType::class, $article);
        // dd($form);

        $form->handleRequest($request);

        dump($event);

        if($form->isSubmitted() && $form->isValid()) {
            if(!$event->getId()){
                $event->setCreatedAt(new \DateTime());
                $event->setOperator($user);
                }
            $manager->persist($event);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre modification a été prise en compte !"
                );

            return $this->redirectToRoute('events/new.html.twig');
        }
            return $this->render('events/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
            'editMode'=> $event->getId()!== null
            ]);
    }


    // /**
    //  * Permet d'avoir accès à la page de détail sur un évènement
    //  * 
    //  * @Route("/events/detail", name="events_detail")
    //  */
    // public function detail(EventRepository $repo, EntityManagerInterface $manager, StatsService $statsService)
    // {   
    //     $eventToCome = $statsService->getEvents('ASC');
    //     $numberOfEvents = $statsService->getCountEvents('ASC');
    
    //     return $this->render('events/detail.html.twig', [

    //         'eventToCome'=> $eventToCome,
    //         'numberOfEvents' => $numberOfEvents

    //     ]);
    // }


    /**
     * //Pour d'afficher les détails d'un évènement qui nous intéresse
     * 
     * @Route("/event/{id}", name="event_show")
     * 
     */
    public function show($id, StatsService $statsService)
    {
        // On récupère l'évènement correspondant à l'id sélectionné
        $event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(['id' => $id]);

        // $eventToCome = $statsService->getEvents('ASC');

        if(!$event){
            throw $this->createNotFoundException("L'article recherché n'existe pas");
        }

        // $repo = $this->getDoctrine()->getRepository(Event::class);

        // $event = $repo->find($id);


        return $this->render('events/show.html.twig', [
            // 'eventToCome'=> $eventToCome,
            'event' => $event,
            
        ]);
    }

}
