<?php

namespace InterNations\UserGroupBundle\Controller;

use InterNations\UserGroupBundle\Entity\Users;
use InterNations\UserGroupBundle\Entity\Groups;
use InterNations\UserGroupBundle\Entity\UsersGroups;
use InterNations\UserGroupBundle\Entity\Roles;
use InterNations\UserGroupBundle\Entity\UsersRoles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 *
 */
class UsersController extends Controller
{
    /**
     * Lists all user entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('UserGroupBundle:Users')->findAll();

        return $this->render('@UserGroup/Users/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Creates a new user entity.
     *
     */
    public function newAction(Request $request)
    {
        $user = new Users();
        $form = $this->createForm('InterNations\UserGroupBundle\Form\UsersType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('users_show', array('id' => $user->getId()));
        }

        return $this->render('@UserGroup/Users/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     */
    public function showAction(Users $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('@UserGroup/Users/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     */
    public function editAction(Request $request, Users $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('InterNations\UserGroupBundle\Form\UsersType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('users_index', array('id' => $user->getId()));
        }

        return $this->render('@UserGroup/Users/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     */
    public function deleteAction(Request $request, Users $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('users_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param Users $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Users $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('users_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Assign User to Group.
     *
     */
    public function assignAction(Request $request, Users $user)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $user->getId();

        $entity = $em
            ->getRepository('UserGroupBundle:UsersGroups')
            ->createQueryBuilder('e')
            ->join('e.groupid', 'r')
            ->where('e.userid = :id')
            ->getQuery()
            ->setParameter('id', $id)
            ->getResult();

        var_dump($entity);exit;

        $query = $em
            ->createQuery(
            'SELECT p, c FROM UserGroupBundle:UsersGroups p
            JOIN p.groupid c
            WHERE p.id = :id'
        )->setParameter('id', $id);

        $groups = $query->getResult();

        var_dump($groups);exit;


        $groupsRepository = $this->getDoctrine()->getRepository(UsersGroups::class);
        $groups = $groupsRepository->findBy(
            array('userid', $id)
        );
        
        var_dump($groups);exit;
        







        var_dump($request);exit;
        $assignForm = $this->createForm('InterNations\UserGroupBundle\Form\UsersType', $user);
        $assignForm->handleRequest($request);

        

        if ($assignForm->isSubmitted() && $assignForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('users_show', array('id' => $user->getId()));
        }

        return $this->render('@UserGroup/Users/userassign.html.twig', array(
            'user' => $user,
            'edit_form' => $assignForm->createView(),
        ));
    }
}
