<?php

namespace InterNations\UserGroupBundle\Controller;

use InterNations\UserGroupBundle\Entity\Users;
use InterNations\UserGroupBundle\Entity\Groups;
use InterNations\UserGroupBundle\Entity\UsersGroups;
use InterNations\UserGroupBundle\Entity\Roles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Groups controller.
 *
 */
class GroupsApiController extends FOSRestController
{
	public function showAction(Groups $group)
    {
        return $group;
    }

    public function indexAction()
    {
        return $this->getDoctrine()->getRepository('UserGroupBundle:Groups')->findAll();
    }

    public function newAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $group = new Groups();
        $group->setName($data['name']);

        $em = $this->getDoctrine()->getManager();

        $em->persist($group);
        $em->flush();

        $view = $this->view($group, 201);
        return $this->handleView($view);
    }

    public function editAction(Groups $group, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $group->setName($data['name']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($group);
        $em->flush();

        return $group;
    }

    public function deleteAction(Groups $group, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Fetch Group Id to prevent deletion if any users are assigned
        $id = $group->getId();

        // Lookup the UsersGroups table for any existing users
        $groupsQuery = $em->createQuery(
            'SELECT q.userid FROM UserGroupBundle:UsersGroups q
             WHERE q.groupid = :id
            '
        )->setParameter('id', $id);

        $groups = $groupsQuery->getResult();

        if (count($groups) > 0) {
        	// Raise error to the form
        	$view = $this->view('Cannot delete group with existing members', 403);
        } else {
        	$em->remove($group);
        	$em->flush();

        	$view = $this->routeRedirectView('groups_api_index', array(), 301);
        }

        return $view;
    }

}
