<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Pizza;
use App\Form\PizzaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/", methods={"get"})
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
     * @Route("/new", methods={"post", "get"})
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

    /**
     * @Route("/{id}", methods={"get"})
     * @param Pizza $entity
     * @return Response
     */
    public function showAction(Pizza $entity)
    {
        return $this->render('Pizza/show.html.twig', [
            'entity' => $entity,
            'delete_form' => $this->createDeleteForm($entity)->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", methods={"post", "get"})
     * @param Request $request
     * @param Pizza $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, Pizza $entity)
    {
        $form = $this->createForm(PizzaType::class, $entity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('app_pizza_index');
        }

        return $this->render('Pizza/form.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", methods={"delete"})
     * @param Request $request
     * @param Pizza $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Pizza $entity)
    {
        $form = $this->createDeleteForm($entity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->remove($entity);
            $this->em->flush();
        }

        return $this->redirectToRoute('app_pizza_index');
    }

    /**
     * @param Pizza $entity
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Pizza $entity)
    {
        $builder = $this->createFormBuilder($entity, [
            'method' => Request::METHOD_DELETE,
            'action' => $this->generateUrl('app_pizza_delete', ['id' => $entity->getId()]),
        ]);

        return $builder->getForm();
    }
}
