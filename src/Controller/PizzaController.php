<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Pizza;
use App\Form\PizzaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pizza")
 */
class PizzaController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * PizzaController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/")
     * @return Response
     */
    public function indexAction()
    {
        $entities = $this->em->getRepository(Pizza::class)->findBy([], ['name' => 'ASC']);

        return $this->render('Pizza/index.html.twig', [
            'entities' => $entities,
        ]);
    }

    /**
     * @Route("/new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $entity = new Pizza();
        $form = $this->createForm(PizzaType::class, $entity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($entity);
            $this->em->flush();

            return $this->redirectToRoute('app_pizza_index');
        }

        return $this->render('Pizza/form.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
        ]);
    }
}