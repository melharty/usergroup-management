<?php

namespace InterNations\UserGroupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use InterNations\UserGroupBundle\Entity\Users;
use InterNations\UserGroupBundle\Entity\UsersGroups;
use InterNations\UserGroupBundle\Entity\Groups;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;

class UsersApiController extends FOSRestController
{
    public function showAction(Users $user)
    {
        return $user;
    }

    public function indexAction()
    {
        return $this->getDoctrine()->getRepository('UserGroupBundle:Users')->findAll();
    }

    public function newAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $user = new Users();
        $user->setUsername($data['username']);
        $user->setPassword(md5($data['password']));
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setEmail($data['email']);

        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        $view = $this->view($user, 201);
        return $this->handleView($view);
    }

    public function editAction(Users $user, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $user->setPassword($data['password'] == '' ? $user->getPassword() : md5($data['password']));
        $user->setFirstname($data['username'] == '' ? $user->getUsername() : $data['firstname']);
        $user->setLastname($data['username'] == '' ? $user->getUsername() : $data['lastname']);
        $user->setEmail($data['username'] == '' ? $user->getUsername() : $data['email']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    public function deleteAction(Users $user, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $view = $this->routeRedirectView('users_api_index', array(), 301);
        return $view;
    }

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
