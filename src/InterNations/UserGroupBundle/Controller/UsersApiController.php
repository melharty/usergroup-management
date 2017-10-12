<?php

namespace InterNations\UserGroupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use InterNations\UserGroupBundle\Entity\Users;
use InterNations\UserGroupBundle\Entity\UsersGroups;
use InterNations\UserGroupBundle\Entity\Groups;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Users API controller.
 *
 */
class UsersApiController extends FOSRestController
{
	/**
	 * Finds and displays a user entity.
	 * @param InterNations\UserGroupBundle\Entity\Users $user
     * @return InterNations\UserGroupBundle\Entity\Users
	 */
    public function showAction(Users $user)
    {
    	// Return the entity and FOS-Rest will handle encoding to JSON
        return $user;
    }

    /**
     * Lists all user entities.
     * @return List view for entity Users
     */
    public function indexAction()
    {
    	// Pull a repository of all Users and return
        // FOS Rest Bundle will handle the conversion
        return $this->getDoctrine()->getRepository('UserGroupBundle:Users')->findAll();
    }

    /**
     * Creates a new user entity.
     * @param Symfony\Component\HttpFoundation $request
     * @return InterNations\UserGroupBundle\Entity\Users
     */
    public function newAction(Request $request)
    {
    	// Pull and decode data from POST
        // TODO: Not the best way to use native json_decode
        // Preferable to use a serializer either the one from
        // Symfony or JMS Serializer Bundle imported by FOS-Rest
        $data = json_decode($request->getContent(), true);

        // Create a new Users Entity to save the data from POST
        $user = new Users();

        // Set the data
        $user->setUsername($data['username']);
        $user->setPassword(md5($data['password']));
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setEmail($data['email']);

        // Create an object of Doctrine Entity Manager
        $em = $this->getDoctrine()->getManager();

        // Persist & flush (Save & Commit)
        $em->persist($user);
        $em->flush();

        // Respond with HTTP Status code 201 (CREATED)
        $view = $this->view($user, 201);
        return $this->handleView($view);
    }

    /**
     * Displays a form to edit an existing user entity.
     * @param Symfony\Component\HttpFoundation $request
     * @param int $id
     * @return InterNations\UserGroupBundle\Entity\Users
     */
    public function editAction(Users $user, Request $request)
    {
    	// Pull and decode data from POST
        // TODO: Not the best way to use native json_decode
        // Preferable to use a serializer either the one from
        // Symfony or JMS Serializer Bundle imported by FOS-Rest
        $data = json_decode($request->getContent(), true);

        // Update the entity with the data from POST
        $user->setPassword($data['password'] == '' ? $user->getPassword() : md5($data['password']));
        $user->setFirstname($data['username'] == '' ? $user->getUsername() : $data['firstname']);
        $user->setLastname($data['username'] == '' ? $user->getUsername() : $data['lastname']);
        $user->setEmail($data['username'] == '' ? $user->getUsername() : $data['email']);

        // Create an object of Doctrine Entity Manager
        $em = $this->getDoctrine()->getManager();

        // Persist & flush (Save & Commit)
        $em->persist($user);
        $em->flush();

        // Return the entity and FOS-Rest will handle encoding to JSON
        return $user;
    }

    /**
     * Deletes a user entity.
     * @param InterNations\UserGroupBundle\Entity\Users $user
     * @param Symfony\Component\HttpFoundation $request
     * @return redirect to route users_api_index
     */
    public function deleteAction(Users $user, Request $request)
    {
        // Create an object of Doctrine Entity Manager
        $em = $this->getDoctrine()->getManager();

        // Remove & flush (Remove & Commit)
        $em->remove($user);
        $em->flush();

        // Set the redirect view to users_api_index after successful deletion
        $view = $this->routeRedirectView('users_api_index', array(), 301);
        return $view;
    }

    /**
     * Assign User to Group(s).
     * @param InterNations\UserGroupBundle\Entity\Users $user
     * @param Symfony\Component\HttpFoundation $request
     * @return Redirect to users_api_show
     */
    public function assignAction(Request $request, Users $user)
    {
        // Create an object of Doctrine Entity Manager
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
        $data = json_decode($request->getContent(), true);

        if ($data !== NULL && array_key_exists("usergroups", $data)) {
            // Process group assignment data
            foreach ($data['usergroups'] as $assignmentGroup) {
                $usersGroups = new UsersGroups();
                $usersGroups->setUserId($id);
                $usersGroups->setGroupId($assignmentGroup);
                $em->persist($usersGroups);
            }

            $em->flush();
        }

        $view = $this->routeRedirectView('users_api_show', array('id' => $id), 301);
        return $view;
    }

    /**
     * Unassign User from Group(s).
     * @param InterNations\UserGroupBundle\Entity\Users $user
     * @param Symfony\Component\HttpFoundation $request
     * @return Redirect to users_api_show
     */
    public function unassignAction(Request $request, Users $user)
    {
        // Create an object of Doctrine Entity Manager
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
        $data = json_decode($request->getContent(), true);

        if ($data !== NULL && array_key_exists("usergroups", $data)) {
            // Delete records from DB
            foreach ($data['usergroups'] as $assignmentGroup) {
                $unassignQuery = $em->createQuery(
                    'DELETE FROM UserGroupBundle:usersGroups q
                    WHERE q.userid = :userid AND q.groupid = :groupid'
                )->setParameter("userid", $id)->setParameter("groupid", $assignmentGroup);

                $unassigned = $unassignQuery->getResult();
            }

            $em->flush();
        }

        $view = $this->routeRedirectView('users_api_show', array('id' => $id), 301);
        return $view;
    }
}
