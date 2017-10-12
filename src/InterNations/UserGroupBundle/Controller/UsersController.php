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
     * Retrieve user entity.
     *
     */
    // protected function fetchUser(Users $user) {
    	
    // }

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
    public function editAction(Request $request, int $id)
    {
    	$user = $this->getDoctrine()->getRepository('UserGroupBundle:Users')->find($id);

        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('InterNations\UserGroupBundle\Form\UsersType', $user);

        $params = $request->request->get('internations_usergroupbundle_users');

        if ($params !== null) {
        	if ($params['password'] != '') $user->setPassword(md5($params['password']));
        	$user->setFirstname($params['firstname']);
        	$user->setLastname($params['lastname']);
        	$user->setEmail($params['email']);
        	
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
     * Assign User to Group(s).
     *
     */
    public function assignAction(Request $request, Users $user)
    {
        // Initialize the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // Fetch the User Id from the URL
        $id = $user->getId();

        // Fetch all groups the user does not belong to
        $groupsQuery = $em->createQuery(
            'SELECT q from UserGroupBundle:Groups q
             WHERE q.id NOT IN (
                SELECT qu.groupid from UserGroupBundle:UsersGroups qu
                WHERE qu.userid = :id
            )'
        )->setParameter('id', $id);

        $groups = $groupsQuery->getResult();

        // Process request params if exists
        $params = $request->request->get('internations_usergroupbundle_users');

        if ($params !== NULL && array_key_exists("usergroups", $params)) {
            // Process group assignment data from POST
            foreach ($params['usergroups'] as $assignmentGroup) {
                $usersGroups = new UsersGroups();
                $usersGroups->setUserId($id);
                $usersGroups->setGroupId($assignmentGroup);
                $em->persist($usersGroups);
            }

            $em->flush();

            return $this->redirectToRoute('users_show', array('id' => $id));
        }

        return $this->render('@UserGroup/Users/userassign.html.twig', array(
            'user' => $user,
            'groups' => $groups
        ));
    }

    /**
     * Unassign User from Group(s).
     *
     */
    public function unassignAction(Request $request, Users $user)
    {
        // Initialize the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // Fetch the User Id from the URL
        $id = $user->getId();

        // Fetch all groups the user does not belong to
        $groupsQuery = $em->createQuery(
            'SELECT q FROM UserGroupBundle:Groups q
             WHERE q.id IN (
                SELECT qu.groupid FROM UserGroupBundle:UsersGroups qu
                WHERE qu.userid = :id
            )'
        )->setParameter('id', $id);

        $groups = $groupsQuery->getResult();

        // Process request params if exists
        $params = $request->request->get('internations_usergroupbundle_users');

        if ($params !== NULL && array_key_exists("usergroups", $params)) {
            // Delete records from DB
            foreach ($params['usergroups'] as $assignmentGroup) {
                $unassignQuery = $em->createQuery(
                    'DELETE FROM UserGroupBundle:usersGroups q
                    WHERE q.userid = :userid AND q.groupid = :groupid'
                )->setParameter("userid", $id)->setParameter("groupid", $assignmentGroup);

                $unassigned = $unassignQuery->getResult();
            }

            $em->flush();

            return $this->redirectToRoute('users_show', array('id' => $id));
        }

        return $this->render('@UserGroup/Users/userassign.html.twig', array(
            'user' => $user,
            'groups' => $groups
        ));
    }
}
